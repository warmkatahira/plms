<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// モデル
use App\Models\SetItem;
use App\Models\SetItemMall;
use App\Models\Job;
use App\Models\ItemUploadHistory;
// サービス
use App\Services\Item\ItemUpload\ItemUploadService;
// 列挙
use App\Enums\ItemUploadEnum;
// 例外
use App\Exceptions\ItemUploadException;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetItemMallMappingUploadJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;              // 最大実行時間を120秒に設定
    public $user_no;                    // プロパティの定義
    public $save_file_full_path;        // プロパティの定義
    public $upload_original_file_name;  // プロパティの定義
    public $upload_target;              // プロパティの定義
    public $upload_type;                // プロパティの定義

    /**
     * Create a new job instance.
     */
    public function __construct($user_no, $save_file_full_path, $upload_original_file_name, $upload_target, $upload_type)
    {
        $this->user_no = $user_no;
        $this->save_file_full_path = $save_file_full_path;
        $this->upload_original_file_name = $upload_original_file_name;
        $this->upload_target = $upload_target;
        $this->upload_type = $upload_type;
    }

    /* public function queue($queue, $job)
    {
        $queue->pushOn('item_upload', $job);
    } */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 現在のjob_idを取得
        $job_id = $this->job->getJobId();
        // カラムにパラメータの値を更新
        Job::where('id', $job_id)->update([
            'user_no' => $this->user_no,
            'upload_file_path' => $this->save_file_full_path,
        ]);
        // ジョブを管理するテーブルにレコードを追加
        $item_upload_history = ItemUploadHistory::create([
            'job_id'            => $job_id,
            'user_no'           => $this->user_no,
            'upload_target'     => $this->upload_target,
            'upload_file_path'  => $this->save_file_full_path,
            'upload_file_name'  => $this->upload_original_file_name,
            'upload_type'       => $this->upload_type,
        ]);
        // 処理タイプを変数にセット
        $upload_type = $this->upload_type;
        // 全データを取得
        $all_line = (new FastExcel)->import($this->save_file_full_path);
        // インポートしたデータのヘッダーを取得
        $data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // ヘッダーを日本語から英語に変換
        $headers = $this->changeHeaderEn($data_header);
        // ファイルのデータを配列化（これをしないとチャンク処理できない）
        $all_line = $all_line->toArray();
        // チャンクサイズの設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($all_line, $chunk_size);
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        try {
            $proc_count = DB::transaction(function () use ($headers, $upload_type, $chunk_size, $chunks, $nowDate, $item_upload_history){
                // チャンク毎のループ処理
                foreach($chunks as $chunk_index => $chunk){
                    // 追加するデータを配列に格納（同時にバリデーションも実施）
                    $data = $this->setArrayImportData($chunk, $headers, $chunk_size, $chunk_index);
                    // バリデーションエラーがある場合
                    if(count(array_filter($data['validation_error'])) != 0){
                        throw new ItemUploadException('データが正しくないため、アップロードできませんでした。', $data['validation_error'], $nowDate, $item_upload_history);
                    }
                    // item_importsテーブルへ追加
                    $this->createArrayImportData($data['create_data']);
                }
                // itemsテーブルへ追加と更新処理
                return $this->procCreateAndUpdate($headers, $upload_type, $nowDate);
            });
        } catch (ItemUploadException $e){
            // インスタンス化
            $ItemUploadService = new ItemUploadService;
            // 渡された内容を取得
            $validation_error = $e->getValidationError();
            $nowDate = $e->getNowDate();
            $item_upload_history = $e->getItemUploadHistory();
            // エラーファイルを作成してテーブルを更新
            $ItemUploadService->item_upload_error_export($validation_error, $nowDate, $item_upload_history, $e->getMessage());
            return;
        }
        // 完了フラグを更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'status' => '完了',
            'message' => '処理件数：'.$proc_count.'件',
        ]);
    }

    public function changeHeaderEn($data_header)
    {
        // 1行のデータを格納する配列をセット
        $param = [];
        // 追加先テーブルのカラム名に合わせて配列を整理
        foreach($data_header as $header){
            // 英語カラムを定義している配列から取得
            $en_column = SetItemMall::column_en_change($header);
            // カラムが空ではない場合
            if($en_column != ''){
                // 配列に変換した英語カラムを格納
                $param[] = $en_column;
            }
        }
        return $param;
    }

    public function setArrayImportData($chunk, $headers, $chunk_size, $chunk_index)
    {
        // 配列をセット
        $create_data = [];
        // 取得したレコードの分だけループ
        foreach($chunk as $line){
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = SetItemMall::column_en_change($key);
                // カラムが空ではない場合
                if($en_column != ''){
                    // 値の調整を行う
                    $adjustment_value = $this->valueAdjustment($key, $value);
                    // 配列に変換した英語カラムを格納
                    $param[$en_column] = $adjustment_value;
                }
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        // バリデーション（共通）
        $validation_error = $this->commonValidation($create_data, $headers, $chunk_size, $chunk_index);
        // エラーメッセージがある場合
        if(!empty($validation_error)){
            // バリデーションエラーを返す
            return compact('validation_error');
        }
        return compact('create_data', 'validation_error');
    }

    public function valueAdjustment($key, $value)
    {
        // 特定のキーのみ値の調整を行う
        switch($key){
            case 'モールセット商品コード':
                // 先頭の「'」を取り除いている
                $adjustment_value = ltrim($value, "'");
                break;
            default:
                // 何もしない
                $adjustment_value = $value;
                break;
        }
        return $adjustment_value === '' ? null : $adjustment_value;
    }

    public function commonValidation($params, $headers, $chunk_size, $chunk_index)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch($column){
                case 'set_item_id':
                    $rules += ['*.'.$column => 'required|exists:set_items,set_item_id'];
                    break;
                case 'mall_id':
                    $rules += ['*.' . $column => [
                        'required',
                        'exists:malls,mall_id',
                        // set_item_idとmall_idの重複チェック
                        function($attribute, $value, $fail) use ($params){
                            // $attribute例: '3.mall_id'のような形式
                            preg_match('/^(\d+)\./', $attribute, $matches);
                            // 行インデックスを取得。該当しない場合はnull
                            $row_index = $matches[1] ?? null;
                            // 行番号が取得でき、かつ対象の行にset_item_idが存在する場合に処理を実行
                            if(!is_null($row_index) && isset($params[$row_index]['set_item_id'])){
                                // 現在の行のset_item_idとmall_idを取得
                                $current_set_item_id = $params[$row_index]['set_item_id'];
                                $current_mall_id = $value;
                                // set_item_idとmall_idを結合して変数に格納
                                $combination = "{$current_set_item_id}-{$current_mall_id}";
                                // 確認された組み合わせを格納する配列を初期化(同じ呼び出し内で最初の1回だけ初期化)
                                static $seen = [];
                                // 配列に今回の組み合わせが存在していた場合
                                if(in_array($combination, $seen, true)){
                                    // エラーを返す
                                    $fail("set_item_id：{$current_set_item_id} と mall_id：{$current_mall_id} の組み合わせが重複しています。");
                                // 配列に今回の組み合わせが存在していない場合
                                }else{
                                    // 配列に今回の組み合わせを格納
                                    $seen[] = $combination;
                                }
                            }
                        },
                    ]];
                    break;
                case 'mall_set_item_code':
                    $rules += ['*.'.$column => 'required|string|max:255'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'              => ':attributeは必須です。',
            'max'                   => ':attribute（:input）は:max文字以内で入力して下さい。',
            'exists'                => ':attribute（:input）はシステムに存在しません。',
            'string'                => ":attribute（:input）は文字列で入力して下さい。",
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.set_item_id'         => 'セット商品ID',
            '*.mall_id'             => 'モールID',
            '*.mall_set_item_code'  => 'モールセット商品コード',
        ];
        // バリデーション実施
        return $this->procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index);
    }

    public function procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index)
    {
        // 配列をセット
        $validation_error = [];
        // バリデーション実施
        $validator = Validator::make($params, $rules, $messages, $attributes);
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->getMessages() as $key => $value){
            // 値を「.」で分割
            $key_explode = explode('.', $key);
            // メッセージを格納
            $validation_error[] = [
                'エラー行数' => ($key_explode[0] + 2) + ($chunk_size * $chunk_index) . '行目',
                'エラー内容' => $value[0],
            ];
        }
        return $validation_error;
    }

    public function createArrayImportData($create_data)
    {
        // 一時テーブルを作成
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_set_item_mall');
        DB::statement('
            CREATE TEMPORARY TABLE temp_set_item_mall (
                set_item_id INT UNSIGNED NOT NULL,
                mall_id INT UNSIGNED NOT NULL,
                mall_set_item_code VARCHAR(255) NOT NULL
            )
        ');
        // 一時テーブルに追加
        DB::table('temp_set_item_mall')->insert($create_data);
    }

    public function procCreateAndUpdate($headers, $upload_type, $nowDate)
    {
        // +-+-+-+-+-+-+-+-+-   セット商品IDとモールIDの組み合わせが存在しない場合は、追加処理を行う   +-+-+-+-+-+-+-+-+-
        // temp_set_item_mallテーブルにしか存在していないレコードを取得
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
            return DB::affectingStatement("
                INSERT INTO set_item_mall (set_item_id, mall_id, mall_set_item_code, created_at, updated_at)
                SELECT 
                    t.set_item_id,
                    t.mall_id,
                    t.mall_set_item_code,
                    NOW(),
                    NOW()
                FROM temp_set_item_mall t
                LEFT JOIN set_item_mall im
                    ON im.set_item_id = t.set_item_id AND im.mall_id = t.mall_id
                WHERE im.set_item_id IS NULL
            ");
        }
        // +-+-+-+-+-+-+-+-+-   セット商品IDとモールIDの組み合わせがset_item_mallテーブルに存在する場合は、更新処理を行う   +-+-+-+-+-+-+-+-+-
        // set_item_mallテーブルとtemp_set_item_mallテーブルを結合して更新に必要なカラムを取得（結合した結果、どっちのテーブルにも存在しているデータ）
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            return DB::table('set_item_mall')
                ->join('temp_set_item_mall', function($join) {
                    $join->on('set_item_mall.set_item_id', '=', 'temp_set_item_mall.set_item_id')
                        ->on('set_item_mall.mall_id', '=', 'temp_set_item_mall.mall_id');
                })
                ->update([
                    'set_item_mall.mall_set_item_code'  => DB::raw('temp_set_item_mall.mall_set_item_code'),
                    'set_item_mall.updated_at'          => $nowDate
                ]);
        }
    }
}
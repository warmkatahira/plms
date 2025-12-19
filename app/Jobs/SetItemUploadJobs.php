<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// モデル
use App\Models\Order;
use App\Models\SetItem;
use App\Models\SetItemDetail;
use App\Models\SetItemImport;
use App\Models\DeliveryCompany;
use App\Models\Job;
use App\Models\ItemUploadHistory;
// サービス
use App\Services\Item\ItemUpload\ItemUploadService;
// 列挙
use App\Enums\ItemUploadEnum;
use App\Enums\OrderStatusEnum;
// 例外
use App\Exceptions\ItemUploadException;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class SetItemUploadJobs implements ShouldQueue
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
                foreach ($chunks as $chunk_index => $chunk){
                    // 追加するデータを配列に格納（同時にバリデーションも実施）
                    $data = $this->setArrayImportData($chunk, $headers, $chunk_size, $chunk_index, $upload_type);
                    // バリデーションエラーがある場合
                    if(count(array_filter($data['validation_error'])) != 0){
                        throw new ItemUploadException('データが正しくないため、アップロードできませんでした。', $data['validation_error'], $nowDate, $item_upload_history);
                    }
                    // item_importsテーブルへ追加
                    $this->createArrayImportData($data['create_data']);
                }
                // 更新処理の場合、更新可能か確認
                $validation_error = $this->checkUpdatable($upload_type);
                // エラーがある場合
                if(count($validation_error) != 0){
                    throw new ItemUploadException('出荷前の受注に存在するセット商品が含まれていたため、更新できませんでした。', $validation_error, $nowDate, $item_upload_history);
                }
                // itemsテーブルへ追加と更新処理
                return $this->procCreateAndUpdate($upload_type);
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
            $en_column = SetItem::column_en_change($header);
            // カラムが空ではない場合
            if($en_column != ''){
                // 配列に変換した英語カラムを格納
                $param[] = $en_column;
            }
        }
        return $param;
    }

    public function setArrayImportData($chunk, $headers, $chunk_size, $chunk_index, $upload_type)
    {
        // 運送会社+配送方法を取得
        $shipping_method_arr = DeliveryCompany::getShippingMethodArr();
        // 配列をセット
        $create_data = [];
        // 取得したレコードの分だけループ
        foreach ($chunk as $line){
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = SetItem::column_en_change($key);
                // カラムが空ではない場合
                if($en_column != ''){
                    // 値の調整を行う
                    $adjustment_value = $this->valueAdjustment($key, $value, $shipping_method_arr);
                    // 配列に変換した英語カラムを格納
                    $param[$en_column] = $adjustment_value;
                }
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        // バリデーション（共通）
        $validation_error = $this->commonValidation($create_data, $headers, $chunk_size, $chunk_index, $upload_type);
        // エラーメッセージがあればバリデーションエラーを配列に格納
        if(!empty($validation_error)){
            return compact('validation_error');
        }
        return compact('create_data', 'validation_error');
    }

    public function valueAdjustment($key, $value, $shipping_method_arr)
    {
        // 特定のキーのみ値の調整を行う
        switch ($key){
            case 'セット商品コード':
            case '構成品商品コード':
                // 半角・全角スペース・シングルクォーテーションを取り除いている
                $adjustment_value = str_replace(array(" ", "　", "'"), "", $value);
                break;
            default:
                // 何もしない
                $adjustment_value = $value;
                break;
        }
        return $adjustment_value === '' ? null : $adjustment_value;
    }

    public function commonValidation($params, $headers, $chunk_size, $chunk_index, $upload_type)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // 構成品商品コードの重複チェックで使用する配列を初期化
        $unique_component_item_codes = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch ($column){
                case 'set_item_code':
                    // createの場合
                    if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
                        $rules += ['*.' . $column => [
                            'required',
                            'max:255',
                            'unique:set_items,set_item_code',
                        ]];
                    // updateの場合
                    }elseif($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
                        $rules += ['*.' . $column => [
                            'required',
                            'max:255',
                            'exists:set_items,set_item_code',
                        ]];
                    }
                    break;
                case 'set_item_name':
                    $rules += ['*.' . $column => [
                        'required',
                        'max:255',
                        function($attribute, $value, $fail) use ($params){
                            // $attribute: '3.shipping_method_id' のような形式
                            preg_match('/^(\d+)\./', $attribute, $matches);
                            // 行インデックスを取得。該当しない場合はnull
                            $row_index = $matches[1] ?? null;
                            // 行番号が取得でき、かつ対象の行にset_item_codeが存在する場合に処理を実行
                            if(!is_null($row_index) && isset($params[$row_index]['set_item_code'])){
                                // 現在の行のset_item_codeを取得
                                $set_item_code = $params[$row_index]['set_item_code'];
                                // 現在の行のset_item_name（＝バリデーション対象の値）を取得
                                $current_set_item_name = $value;
                                // 比較用のset_item_nameを初期化（最初に出てきたものを記録）
                                $expected_set_item_name = null;
                                // 全行をループして、同じset_item_codeを持つ行を探す
                                foreach($params as $i => $row){
                                    // 同じset_item_codeの行のみ対象にする
                                    if(($row['set_item_code'] ?? null) === $set_item_code){
                                        // set_item_nameが設定されている場合に比較を実施
                                        if(isset($row['set_item_name'])){
                                            // nullの場合
                                            if(is_null($expected_set_item_name)){
                                                // 最初に見つけたset_item_nameを記録
                                                $expected_set_item_name = $row['set_item_name'];
                                            }elseif($row['set_item_name'] !== $expected_set_item_name){
                                                // 以降で異なるset_item_nameを見つけた場合はバリデーションエラー
                                                $fail("セット商品コード ：{$set_item_code} に対するセット商品名が統一されていません。");
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    ]];
                    break;
                case 'component_item_code':
                    $rules += ['*.' . $column => [
                        'required',
                        'max:255',
                        'exists:items,item_code',
                        function($attribute, $value, $fail) use (&$unique_component_item_codes, $params){
                            // $attribute: '*.component_item_code' → '3.component_item_code' のような形式
                            // この中から行インデックス（3の部分）を抽出する
                            preg_match('/^(\d+)\./', $attribute, $matches);
                            // 行インデックスを取得。該当しない場合はnull
                            $row_index = $matches[1] ?? null;
                            // 行インデックスが取得でき、かつその行にset_item_codeが存在する場合
                            if($row_index !== null && isset($params[$row_index]['set_item_code'])){
                                // 同じ行のset_item_codeの値を取得
                                $set_item_code = $params[$row_index]['set_item_code'];
                                // set_item_codeとcomponent_item_codeの組み合わせをキーとして作成
                                $combo_key = $set_item_code . ' / ' . $value;
                                // 同じ組み合わせがすでにバリデーション中に出現していればエラー
                                if(in_array($combo_key, $unique_component_item_codes)){
                                    // エラーメッセージを返す
                                    $fail('セット商品コードと構成品商品コードの組み合わせが重複しています。('.$combo_key.')');
                                }else{
                                    // 初めての組み合わせであれば配列に追加
                                    $unique_component_item_codes[] = $combo_key;
                                }
                            }
                        }
                    ]];
                    break;
                case 'component_quantity':
                    $rules += ['*.'.$column => 'required|integer|min:1'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'              => ':attributeは必須です。',
            'max'                   => ':attribute（:input）は:max文字以内で入力して下さい。',
            'min'                   => ':attribute（:input）は:min以上で入力して下さい。',
            'exists'                => ':attribute（:input）はシステムに存在しません。',
            'integer'               => ':attribute（:input）は数値で入力して下さい。',
            'unique'                => ':attribute（:input）は既に使用されています。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.set_item_code'       => 'セット商品コード',
            '*.set_item_name'       => 'セット商品名',
            '*.component_item_code' => '構成品商品コード',
            '*.component_quantity'  => '構成数',
        ];
        // バリデーション実施
        return $this->processValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index);
    }

    public function processValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index)
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
        // テーブルをロック
        SetItemImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        SetItemImport::query()->delete();
        // 追加用の配列に入っている情報をテーブルに追加
        SetItemImport::insert($create_data);
        // set_item_codeとitem_codeでリレーションを張り、item_idをcomponent_item_idに反映
        DB::table('set_item_imports')
            ->join('items', 'items.item_code', 'set_item_imports.component_item_code')
            ->update([
                'set_item_imports.component_item_id' => DB::raw('items.item_id')
            ]);
    }

    public function checkUpdatable($upload_type)
    {
        // 更新処理の場合
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            // 更新対象のセット商品のセット商品IDを重複を除いて取得
            $set_item_ids = SetItemImport::join('set_items', 'set_items.set_item_code', 'set_item_imports.set_item_code')
                                ->distinct()
                                ->pluck('set_items.set_item_id');
            // 出荷済み前の受注で、更新対象のセット商品が存在している受注を取得
            $set_items = Order::where('order_status_id', '<', OrderStatusEnum::SHUKKA_ZUMI)
                            ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                            ->whereIn('allocated_set_item_id', $set_item_ids)
                            ->select('allocated_set_item_id')
                            ->groupBy('allocated_set_item_id')
                            ->get();
            // 受注が存在している場合
            if($set_items->isNotEmpty()){
                // 配列をセット
                $validation_error = [];
                // レコードの分だけループ処理
                foreach($set_items as $set_item){
                    // メッセージを格納
                    $validation_error[] = [
                        'エラー行数' => 'セット商品ID',
                        'エラー内容' => $set_item->allocated_set_item_id,
                    ];
                }
                return $validation_error;
            }
        }
        return array();
    }

    public function procCreateAndUpdate($upload_type)
    {
        // +-+-+-+-+-+-+-+-+-   セット商品コードがitemsテーブルに存在しない場合は、追加処理を行う   +-+-+-+-+-+-+-+-+-
        // set_item_importsテーブルにしか存在していないレコードを取得(セット商品マスタに追加するカラムだけ取得)
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
            // set_itemsに存在しないレコードを取得
            $set_item_imports = SetItemImport::doesntHave('set_item')->get();
            // set_item_code毎にまとめる
            $grouped = $set_item_imports->groupBy('set_item_code');
            // set_item_codeの分だけループ処理
            foreach($grouped as $set_item_code => $rows){
                // 現在のset_item_codeに関連する1レコードのみを取得
                $first = $rows->first();
                // set_itemを追加
                $set_item = SetItem::create([
                    'set_item_code' => $first->set_item_code,
                    'set_item_name' => $first->set_item_name,
                ]);
                // set_item_codeの構成品の分だけループ処理
                foreach($rows as $row){
                    // set_item_detailsを追加
                    SetItemDetail::create([
                        'set_item_id'           => $set_item->set_item_id,
                        'component_item_id'     => $row->component_item_id,
                        'component_quantity'    => $row->component_quantity,
                    ]);
                }
            }
            return count($grouped);
        }
        // +-+-+-+-+-+-+-+-+-   セット商品コードがitemsテーブルに存在する場合は、更新処理を行う   +-+-+-+-+-+-+-+-+-
        // set_itemsテーブルとset_item_importsテーブルを結合して更新に必要なカラムを取得（結合した結果、どっちのテーブルにも存在しているデータ）
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            // set_itemsに存在するレコードを取得
            $set_item_imports = SetItemImport::join('set_items', 'set_items.set_item_code', 'set_item_imports.set_item_code')
                                    ->select('set_items.set_item_id', 'set_items.set_item_code', 'set_item_imports.set_item_name', 'set_item_imports.component_item_id', 'set_item_imports.component_quantity')
                                    ->get();
            // set_item_code毎にまとめる
            $grouped = $set_item_imports->groupBy('set_item_id');
            // 更新対象のセット商品詳細を削除
            SetItemDetail::whereIn('set_item_id', $set_item_imports->pluck('set_item_id')->unique())->delete();
            // set_item_codeの分だけループ処理
            foreach($grouped as $set_item_code => $rows){
                // 現在のset_item_codeに関連する1レコードのみを取得
                $first = $rows->first();
                // set_itemを更新
                SetItem::getSpecify($first->set_item_id)->update([
                    'set_item_name' => $first->set_item_name,
                ]);
                // set_item_codeの構成品の分だけループ処理
                foreach($rows as $row){
                    // set_item_detailsを追加
                    SetItemDetail::create([
                        'set_item_id'           => $row->set_item_id,
                        'component_item_id'     => $row->component_item_id,
                        'component_quantity'    => $row->component_quantity,
                    ]);
                }
            }
            return count($grouped);
        }
    }
}
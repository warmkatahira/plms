<?php

namespace App\Services\Item\ItemUpload;

// モデル
use App\Models\Item;
use App\Models\ItemImport;
use App\Models\ItemUploadHistory;
// 列挙
use App\Enums\ItemUploadEnum;
// その他
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemUploadService
{
    // 選択したデータをストレージにインポート
    public function importData($select_file)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 選択したデータのファイル名を取得
        $upload_original_file_name = $select_file->getClientOriginalName();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'item_upload_data_'.$nowDate->format('Y-m-d H-i-s').'.csv';
        // ファイルを保存して保存先のパスを取得
        $path = Storage::disk('public')->putFileAs('upload/item_upload', $select_file, $save_file_name);
        // フルパスに調整する
        return with([
            'upload_original_file_name' => $upload_original_file_name,
            'save_file_full_path' => Storage::disk('public')->path($path),
        ]);
    }

    // インポートしたデータのヘッダーを確認
    public function checkHeader($save_file_full_path, $upload_target, $upload_type)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_full_path);
        // インポートしたデータのヘッダーを取得
        $data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // 対象によって処理を分岐
        // 対象=商品の場合
        if($upload_target === ItemUploadEnum::UPLOAD_TARGET_ITEM){
            // タイプ=追加の場合
            if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
                // チェックする項目を配列に格納
                $check_column = ItemUploadEnum::REQUIRED_HEADER_ITEM_CREATE;
            }
            // タイプ=更新の場合
            if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
                // チェックする項目を配列に格納
                $check_column = ItemUploadEnum::REQUIRED_HEADER_ITEM_UPDATE;
            }
        }
        // 対象=セット商品の場合
        if($upload_target === ItemUploadEnum::UPLOAD_TARGET_SET_ITEM){
            // チェックする項目を配列に格納
            $check_column = ItemUploadEnum::REQUIRED_HEADER_SET_ITEM_CREATE_UPDATE;
        }
        // 対象=モール×単品商品マッピングの場合
        if($upload_target === ItemUploadEnum::UPLOAD_TARGET_ITEM_MALL_MAPPING){
            // チェックする項目を配列に格納
            $check_column = ItemUploadEnum::REQUIRED_HEADER_ITEM_MALL_MAPPING_CREATE_UPDATE;
        }
        // 対象=モール×セット商品マッピングの場合
        if($upload_target === ItemUploadEnum::UPLOAD_TARGET_SET_ITEM_MALL_MAPPING){
            // チェックする項目を配列に格納
            $check_column = ItemUploadEnum::REQUIRED_HEADER_SET_ITEM_MALL_MAPPING_CREATE_UPDATE;
        }
        // チェックするカラムの分だけループ処理
        foreach($check_column as $column){
            // カラムが存在するか確認
            $result = $this->checkValueExists($data_header, $column);
            // nullでなければエラーを返す
            if(!is_null($result)){
                return $result;
            }
        }
        return null;
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value){
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }

    public function item_upload_error_export($validation_error, $nowDate, $item_upload_history, $message)
    {
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($validation_error, $chunk_size);
        // ファイル名を設定
        $error_file_name = '商品アップロードエラー_'.$nowDate->format('Y-m-d H-i-s').'_'.$item_upload_history->user_no.'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/error/'.$error_file_name);
        // エラーファイル名を更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'error_file_name'   => $error_file_name,
            'status'            => '失敗',
            'message'           => $message,
        ]);
        // ヘッダ行を書き込む
        $header = ['エラー行数', 'エラー内容'];
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach($chunks as $chunk){
            // レコード毎のループ処理
            foreach ($chunk as $item){
                // CSV形式で内容をセット
                $row = [$item['エラー行数'], $item['エラー内容']];
                $csvContent .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
    }
}
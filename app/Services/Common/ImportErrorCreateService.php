<?php

namespace App\Services\Common;

// 列挙
use App\Enums\SystemEnum;

class ImportErrorCreateService
{
    // インポートエラー情報のファイルを作成
    public function createImportError($file_name_prefix, $error, $nowDate, $header)
    {
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($error, $chunk_size);
        // ファイル名を設定
        $csvFileName = '【'.SystemEnum::getSystemTitle().'】'.$file_name_prefix.'_'.$nowDate->format('Y-m-d H-i-s').'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/error/'.$csvFileName);
        // ヘッダーを書き込む
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach($chunks as $chunk){
            // レコード毎のループ処理
            foreach($chunk as $item){
                // $itemの全要素を結合してCSV行にする
                $csvContent .= implode(',', $item) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
        return $csvFileName;
    }
}
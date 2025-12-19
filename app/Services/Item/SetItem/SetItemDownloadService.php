<?php

namespace App\Services\Item\SetItem;

// モデル
use App\Models\SetItem;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class SetItemDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($set_items)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($set_items, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = SetItem::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $set_items->chunk($chunk_size, function ($set_items) use ($handle){
                // セット商品の分だけループ処理
                foreach($set_items as $set_item){
                    // セット商品詳細の分だけループ処理
                    foreach($set_item->set_item_details as $set_item_detail){
                        // 変数に情報を格納
                        $row = [
                            $set_item->set_item_id,
                            $set_item->set_item_code,
                            $set_item->set_item_name,
                            $set_item_detail->item->item_code,
                            $set_item_detail->component_quantity,
                            $set_item->set_item_image_file_name === SystemEnum::DEFAULT_IMAGE_FILE_NAME ? 'なし' : 'あり',
                            CarbonImmutable::parse($set_item->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
                        ];
                        // 書き込む
                        fputcsv($handle, $row);
                    }
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}
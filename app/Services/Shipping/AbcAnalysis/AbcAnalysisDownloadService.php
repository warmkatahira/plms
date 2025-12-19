<?php

namespace App\Services\Shipping\AbcAnalysis;

// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class AbcAnalysisDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($items)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($items, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = ['ランク', '商品JANコード', '商品名', '出荷数', '構成比', '累積構成比'];
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            foreach($items->chunk($chunk_size) as $chunk){
                // 商品の分だけループ処理
                foreach($chunk as $item){
                    // 変数に情報を格納
                    $row = [
                        $item->rank,
                        $item->item_jan_code,
                        $item->item_name,
                        $item->total_ship_quantity,
                        $item->ratio,
                        $item->cumulative_ratio,
                    ];
                    // 書き込む
                    fputcsv($handle, $row);
                };
            }
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}
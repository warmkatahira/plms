<?php

namespace App\Services\Admin\BoardingLocation;

// モデル
use App\Models\BoardingLocation;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class BoardingLocationDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($boarding_locations)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($boarding_locations, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = BoardingLocation::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $boarding_locations->chunk($chunk_size, function ($boarding_locations) use ($handle){
                // 乗降場所の分だけループ処理
                foreach($boarding_locations as $boarding_location){
                    // 変数に情報を格納
                    $row = [
                        $boarding_location->is_active_text,
                        $boarding_location->location_name,
                        $boarding_location->location_memo,
                        $boarding_location->sort_order,
                        CarbonImmutable::parse($boarding_location->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
                    ];
                    // 書き込む
                    fputcsv($handle, $row);
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}
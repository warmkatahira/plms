<?php

namespace App\Services\Admin\Vehicle;

// モデル
use App\Models\Vehicle;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class VehicleDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($vehicles)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($vehicles, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = Vehicle::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $vehicles->chunk($chunk_size, function ($vehicles) use ($handle){
                // 車両の分だけループ処理
                foreach($vehicles as $vehicle){
                    // 変数に情報を格納
                    $row = [
                        $vehicle->is_active_text,
                        $vehicle->vehicle_type->vehicle_type,
                        $vehicle->vehicle_category->vehicle_category,
                        $vehicle->owner,
                        $vehicle->vehicle_name,
                        $vehicle->vehicle_color,
                        $vehicle->vehicle_number,
                        $vehicle->vehicle_capacity,
                        $vehicle->vehicle_memo,
                        CarbonImmutable::parse($vehicle->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
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
<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class RouteDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($routes)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($routes, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = Route::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $routes->chunk($chunk_size)->each(function ($routes) use ($handle) {
                // ルートの分だけループ処理
                foreach($routes as $route){
                    // ルート詳細の分だけループ処理
                    foreach($route->route_details as $route_detail){
                        // 出発時刻を取得
                        $dep = $route_detail->departure_time ? CarbonImmutable::parse($route_detail->departure_time)->format('H:i') : null;
                        // 到着時刻を取得
                        $arr = $route_detail->arrival_time ? CarbonImmutable::parse($route_detail->arrival_time)->format('H:i') : null;
                        // 情報があるものに合わせて変数に格納
                        if($arr && $dep){
                            $dep_arr = $arr.' 着 → '. $dep. ' 発';
                        }elseif($arr){
                            $dep_arr = $arr.' 着';
                        }elseif($dep){
                            $dep_arr = $dep. ' 発';
                        }else{
                            $dep_arr = '—';
                        }
                        // 次の地点までの時間を取得
                        if($route_detail->required_minutes !== null){
                            $next_time = $route_detail->required_minutes.' 分';
                        }else{
                            $next_time = '—';
                        }
                        // 変数に情報を格納
                        $row = [
                            $route->is_active_text,
                            $route->route_type->route_type,
                            $route->vehicle_category->vehicle_category,
                            $route->route_name,
                            $route->route_details->count(),
                            $route->sort_order,
                            CarbonImmutable::parse($route->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
                            $route_detail->boarding_location->location_name,
                            $route_detail->boarding_location->location_memo,
                            $route_detail->stop_order,
                            $dep_arr,
                            $next_time,
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
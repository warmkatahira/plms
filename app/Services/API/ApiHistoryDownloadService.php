<?php

namespace App\Services\API;

// モデル
use App\Models\ApiHistory;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ApiHistoryDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($api_histories)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($api_histories, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = ApiHistory::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $api_histories->chunk($chunk_size, function ($api_histories) use ($handle){
                // 商品の分だけループ処理
                foreach($api_histories as $api_history){
                    // 変数に情報を格納
                    $row = [
                        CarbonImmutable::parse($api_history->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
                        $api_history->mall->mall_name,
                        $api_history->api_action->api_action_name,
                        $api_history->api_status->api_status_name,
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
<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class UserDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($users)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($users, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = User::downloadHeaderByUser();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $users->chunk($chunk_size, function ($users) use ($handle){
                // 従業員の分だけループ処理
                foreach($users as $user){
                    // 変数に情報を格納
                    $row = [
                        $user->is_active_text,
                        $user->base?->base_name,
                        $user->user_id,
                        $user->employee_no,
                        $user->user_name,
                        $user->email,
                        $user->role->role_name,
                        $user->is_password_change_required_text,
                        $user->last_login_at ? CarbonImmutable::parse($user->last_login_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒') : '',
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
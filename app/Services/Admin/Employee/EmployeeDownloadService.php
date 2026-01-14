<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class EmployeeDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($employees)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($employees, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = User::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $employees->chunk($chunk_size, function ($employees) use ($handle){
                // 従業員の分だけループ処理
                foreach($employees as $employee){
                    // 変数に情報を格納
                    $row = [
                        $employee->status_text,
                        $employee->base->short_base_name,
                        "'".$employee->employee_no,
                        $employee->user_name,
                        $employee->user_id,
                        '',
                        $employee->paid_leave_granted_days,
                        $employee->paid_leave_remaining_days,
                        $employee->paid_leave_used_days,
                        $employee->daily_working_hours,
                        $employee->half_day_working_hours,
                        $employee->is_auto_update_statutory_leave_remaining_days_text,
                        $employee->statutory_leave_expiration_date ? CarbonImmutable::parse($employee->statutory_leave_expiration_date)->isoFormat('YYYY/MM/DD') : null,
                        $employee->statutory_leave_days,
                        $employee->statutory_leave_remaining_days,
                        CarbonImmutable::parse($employee->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒'),
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
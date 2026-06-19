<?php

namespace App\Services\Admin\FileImport;

// モデル
use App\Models\User;
use App\Models\FileImport;

class UserUpdateService
{
    // usersテーブルに存在していて今回の取り込みに存在していない従業員のis_activeを無効に更新
    public function deactivateMissingEmployees()
    {
        // Userに存在するemployee_noを取得
        $existing_employee_nos = User::where('is_active', true)->pluck('employee_no')->toArray();
        // FileImportに存在するemployee_noを取得
        $import_employee_nos = FileImport::pluck('employee_no')->toArray();
        // Userに存在してFileImportに存在しないemployee_noを抽出
        $missing_employee_nos = array_diff($existing_employee_nos, $import_employee_nos);
        // 該当する従業員がいなければ終了
        if (empty($missing_employee_nos)) return;
        // 該当する従業員の情報を取得
        $missing_employees = User::whereIn('employee_no', $missing_employee_nos)
                                ->get(['employee_no', 'user_name']);
        // is_activeを無効に更新
        User::whereIn('employee_no', $missing_employee_nos)->update(['is_active' => false]);
        // 従業員番号と氏名をメッセージ用に整形して返す
        return $missing_employees->map(fn($e) => "{$e->employee_no}：{$e->user_name}")->implode("\n");
    }

    // 所属を更新
    public function updateBase()
    {
        // base_infoの括弧内キーワードとbase_idのマッピング
        $base_map = [
            '第１'              => '1st',
            'IMP三郷'           => 'IMP',
            '第２'              => '2nd',
            'ロジステーション'  => 'LS',
            '第３'              => '3rd',
            'ロジコンタクト'    => 'LC',
            'ロジポート'        => 'LP',
            '広島'              => 'HR',
            '本社'              => 'honsha',
        ];
        // FileImportからパートを含むレコードのみ取得
        $import_records = FileImport::where('base_info', 'like', 'パート%')
                                    ->get(['employee_no', 'base_info']);
        // 対象者の分だけループ処理
        foreach ($import_records as $record) {
            // 括弧内の文字列を抽出（全角・半角両対応）
            if (!preg_match('/[（(](.+?)[）)]/u', $record->base_info, $matches)) {
                continue;
            }
            $keyword = $matches[1];
            // マッピングにないキーワードはスキップ
            if (!isset($base_map[$keyword])) {
                continue;
            }
            $new_base_id = $base_map[$keyword];
            // 現在のbase_idと差分がある場合のみ更新
            $user = User::where('employee_no', $record->employee_no)
                        ->where('is_active', true)
                        ->first(['user_no', 'base_id']);
            if ($user && $user->base_id !== $new_base_id) {
                $user->update(['base_id' => $new_base_id]);
            }
        }
    }
}
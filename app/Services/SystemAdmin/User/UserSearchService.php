<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// サービス
use App\Services\Common\BaseFilterService;
// その他
use Illuminate\Support\Facades\DB;

class UserSearchService extends BaseFilterService
{
    // ベースクエリ
    protected function baseQuery()
    {
        // クエリをセット
        return User::with(['role', 'base'])
                    ->select('users.*')
                    ->selectRaw("
                        CONCAT(
                            TIMESTAMPDIFF(YEAR, hire_date, NOW()), '年',
                            MOD(TIMESTAMPDIFF(MONTH, hire_date, NOW()), 12), 'ヶ月'
                        ) as service_years
                    ");
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_user_id',
            'filter_user_name',
            'filter_email',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [
            // 権限
            'filter_role_id' => function ($query, $value) {
                $query->whereHas('role', function ($q) use ($value) {
                    $q->where('role_id', $value);
                });
            },
            // 営業所
            'filter_base_id' => function ($query, $value) {
                $query->whereHas('base', function ($q) use ($value) {
                    $q->where('base_id', $value);
                });
            },
            // 勤続年数
            'filter_service_years' => function ($query, $value) {
                $query->whereRaw("
                    CONCAT(
                        TIMESTAMPDIFF(YEAR, hire_date, NOW()), '年',
                        MOD(TIMESTAMPDIFF(MONTH, hire_date, NOW()), 12), 'ヶ月'
                    ) LIKE ?
                ", ["%{$value}%"]);
            },
            // 総保有日数
            'filter_total_days' => function ($query, $value) {
                $query->whereRaw('(COALESCE(carried_over_days, 0) + COALESCE(granted_days, 0)) = ?', [(float) $value]);
            },
            // 残日数
            'filter_remaining_days' => function ($query, $value) {
                $query->whereRaw('(COALESCE(carried_over_days, 0) + COALESCE(granted_days, 0) - COALESCE(used_days, 0)) = ?', [(float) $value]);
            },
            // 総義務日数
            'filter_total_required_days' => function ($query, $value) {
                $query->whereRaw('(COALESCE(carried_over_required_days, 0) + COALESCE(granted_required_days, 0)) = ?', [(float) $value]);
            },
            // 義務残日数
            'filter_remaining_required_days' => function ($query, $value) {
                $query->whereRaw('GREATEST(0, COALESCE(carried_over_required_days, 0) + COALESCE(granted_required_days, 0) - COALESCE(used_days, 0)) = ?', [(float) $value]);
            },
            // 次回付与年月
            'filter_next_grant_year_month' => function ($query, $value) {
                // input[type=month]は"yyyy-mm"形式で送られてくるので"yyyymm"に変換
                $query->where('next_grant_year_month', str_replace('-', '', $value));
            },
            // 使用日数リセット年月
            'filter_used_days_reset_year_month' => function ($query, $value) {
                // input[type=month]は"yyyy-mm"形式で送られてくるので"yyyymm"に変換
                $query->where('used_days_reset_year_month', str_replace('-', '', $value));
            },
        ];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('users.user_no', 'asc');
    }
}
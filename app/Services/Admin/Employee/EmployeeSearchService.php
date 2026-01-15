<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
// 列挙
use App\Enums\RoleEnum;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_status',
            'search_base_id',
            'search_user_name',
            'search_is_auto_update_statutory_leave_remaining_days',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            session(['search_status' => '1']);
            // 所長は自営業所だけなので、デフォルトで自営業所をセット
            if(Auth::user()->role_id === RoleEnum::BASE_ADMIN){
                session(['search_base_id' => Auth::user()->base_id]);
            }
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_status' => $request->search_status]);
            session(['search_user_name' => $request->search_user_name]);
            session(['search_is_auto_update_statutory_leave_remaining_days' => $request->search_is_auto_update_statutory_leave_remaining_days]);
            // 権限によって検索値を使用するか固定値を使用するか分岐
            // 所長は自営業所だけなので固定値、それ以外は検索値
            if(Auth::user()->role_id === RoleEnum::BASE_ADMIN){
                session(['search_base_id' => Auth::user()->base_id]);
            }else{
                session(['search_base_id' => $request->search_base_id]);
            }
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = User::join('paid_leaves', 'paid_leaves.user_no', 'users.user_no')
                    ->join('statutory_leaves', 'statutory_leaves.user_no', 'users.user_no')
                    ->with('base')
                    ->where('role_id', RoleEnum::USER);
        // ステータスの条件がある場合
        if(session('search_status') != null){
            // 条件を指定して取得
            $query = $query->where('status', session('search_status'));
        }
        // 営業所IDの条件がある場合
        if (session('search_base_id') != null) {
            $query = $query->where('base_id', session('search_base_id'));
        }
        // 氏名の条件がある場合
        if(session('search_user_name') != null){
            // 条件を指定して取得
            $query = $query->where('user_name', 'LIKE', '%'.session('search_user_name').'%');
        }
        // 義務残日数自動更新の条件がある場合
        if(session('search_is_auto_update_statutory_leave_remaining_days') != null){
            // 条件を指定して取得
            $query = $query->where('is_auto_update_statutory_leave_remaining_days', session('search_is_auto_update_statutory_leave_remaining_days'));
        }
        // 並び替えを実施
        return $query->orderBy('employee_no', 'asc');
    }
}
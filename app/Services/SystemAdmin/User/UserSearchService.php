<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// その他
use Illuminate\Support\Facades\DB;

class UserSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_status',
            'search_user_name',
            'search_owned_vehicle',
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
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_status' => $request->search_status]);
            session(['search_user_name' => $request->search_user_name]);
            session(['search_owned_vehicle' => $request->search_owned_vehicle]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = User::with(['role', 'vehicles']);
        // ステータスの条件がある場合
        if(session('search_status') != null){
            // 条件を指定して取得
            $query = $query->where('status', session('search_status'));
        }
        // 氏名の条件がある場合
        if(session('search_user_name') != null){
            // 条件を指定して取得
            $query = $query->where(function ($q) {
                $q->where('last_name', 'LIKE', '%' . session('search_user_name') . '%')
                    ->orWhere('first_name', 'LIKE', '%' . session('search_user_name') . '%');
            });
        }
        // 所有車両の条件がある場合
        if(session('search_owned_vehicle') != null){
            if(session('search_owned_vehicle')){
                // 車両を1台以上持っているユーザー
                $query = $query->whereHas('vehicles');
            }else{
                // 車両を持っていないユーザー
                $query = $query->whereDoesntHave('vehicles');
            }
        }
        // 並び替えを実施
        return $query->orderBy('user_no', 'asc');
    }
}
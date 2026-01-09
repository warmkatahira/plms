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
            'search_base_id',
            'search_user_name',
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
            session(['search_base_id' => $request->search_base_id]);
            session(['search_user_name' => $request->search_user_name]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = User::with('base');
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
        // 並び替えを実施
        return $query->orderBy('employee_no', 'asc');
    }
}
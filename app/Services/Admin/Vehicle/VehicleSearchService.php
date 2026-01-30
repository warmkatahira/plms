<?php

namespace App\Services\Admin\Vehicle;

// モデル
use App\Models\Vehicle;
// 列挙
use App\Enums\RoleEnum;
use App\Enums\VehicleEnum;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VehicleSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
            'search_vehicle_type_id',
            'search_vehicle_category_id',
            'search_vehicle_capacity',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            session(['search_is_active' => '1']);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_is_active' => $request->search_is_active]);
            session(['search_vehicle_type_id' => $request->search_vehicle_type_id]);
            session(['search_vehicle_category_id' => $request->search_vehicle_category_id]);
            session(['search_vehicle_capacity' => $request->search_vehicle_capacity]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Vehicle::with(['user', 'vehicle_type', 'vehicle_category']);
        // 利用可否の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // 車両区分の条件がある場合
        if(session('search_vehicle_type_id') != null){
            // 条件を指定して取得
            $query = $query->where('vehicle_type_id', session('search_vehicle_type_id'));
        }
        // 車両種別の条件がある場合
        if(session('search_vehicle_category_id') != null){
            // 条件を指定して取得
            $query = $query->where('vehicle_category_id', session('search_vehicle_category_id'));
        }
        // 送迎可能人数の条件がある場合
        if(session('search_vehicle_capacity') != null){
            // 「4人以下」の場合
            if(session('search_vehicle_capacity') == VehicleEnum::VEHICLE_CAPACITY_4){
                $query = $query->where('vehicle_capacity', '<=', 4);
            }
            // 「5人以上」の場合
            if(session('search_vehicle_capacity') == VehicleEnum::VEHICLE_CAPACITY_5){
                $query = $query->where('vehicle_capacity', '>=', 5);
            }
        }
        // 並び替えを実施
        return $query->orderBy('vehicle_id', 'asc');
    }
}
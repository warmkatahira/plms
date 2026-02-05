<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class RouteSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
            'search_route_type_id',
            'search_vehicle_category_id',
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
            session(['search_route_type_id' => $request->search_route_type_id]);
            session(['search_vehicle_category_id' => $request->search_vehicle_category_id]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Route::with(['route_details.boarding_location', 'vehicle_category', 'route_type']);
        // 利用可否の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // ルート区分の条件がある場合
        if(session('search_route_type_id') != null){
            // 条件を指定して取得
            $query = $query->where('route_type_id', session('search_route_type_id'));
        }
        // 車両種別の条件がある場合
        if(session('search_vehicle_category_id') != null){
            // 条件を指定して取得
            $query = $query->where('vehicle_category_id', session('search_vehicle_category_id'));
        }
        // 並び替えを実施
        return $query->orderBy('sort_order', 'asc');
    }

    // 所要時間を取得
    public function getRequiredMinutes($routes)
    {
        // Builder / Collection / Paginator のいずれが来ても動くようにする分岐

        // ページネーション（LengthAwarePaginator）が渡ってきた場合
        if ($routes instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            // Paginator の「中身の Collection」だけを取り出して処理する
            // ページネーション構造自体は壊さない
            $routes->getCollection()->each(function ($route) {
                $this->calcMinutesForRoute($route);
            });
            // 加工済みの Paginator をそのまま返す
            return $routes;
        }
        // クエリビルダ（Builder）が渡ってきた場合
        $routes = $routes instanceof \Illuminate\Database\Eloquent\Builder ? $routes->get() : $routes;
        $routes->each(function ($route) {
            $this->calcMinutesForRoute($route);
        });
        // 加工済みの Collection を返す
        return $routes;
    }

    // 所要時間の計算処理
    private function calcMinutesForRoute($route)
    {
        // ルート詳細を取得
        $details = $route->route_details->sortBy('stop_order')->values();
        // ルート詳細の分だけループ処理
        $details->each(function ($detail, $index) use ($details) {
            // 次の停車場所を取得
            $next = $details->get($index + 1);
            // 次の停車場所がある場合
            if($next){
                // 次の地点までの時間を取得
                $detail->required_minutes = CarbonImmutable::parse($detail->departure_time)->diffInMinutes(CarbonImmutable::parse($next->arrival_time));
            }else{
                // nullを格納
                $detail->required_minutes = null;
            }
        });
        $route->setRelation('route_details', $details);
    }
}
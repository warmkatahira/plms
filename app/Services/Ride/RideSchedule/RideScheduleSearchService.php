<?php

namespace App\Services\Ride\RideSchedule;

// モデル
use App\Models\Ride;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class RideScheduleSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
            'search_schedule_date_from',
            'search_schedule_date_to',
            'search_driver_status',
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
            session(['search_schedule_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['search_schedule_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_is_active' => $request->search_is_active]);
            session(['search_schedule_date_from' => $request->search_schedule_date_from]);
            session(['search_schedule_date_to' => $request->search_schedule_date_to]);
            session(['search_driver_status' => $request->search_driver_status]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Ride::with(['route_type', 'user', 'vehicle', 'ride_details.ride_users.user']);
        // 運行状況の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // 送迎日の条件がある場合
        if(!empty(session('search_schedule_date_from')) && !empty(session('search_schedule_date_to'))){
            $query = $query->whereDate('schedule_date', '>=', session('search_schedule_date_from'))
                            ->whereDate('schedule_date', '<=', session('search_schedule_date_to'));
        }
        // ドライバーの条件がある場合
        if(session('search_driver_status') != null){
            // 「確定」の場合
            if(session('search_driver_status')){
                $query = $query->whereNotNull('driver_user_no');
            // 「未定」の場合
            }elseif(!session('search_driver_status')){
                $query = $query->whereNull('driver_user_no');
            }
        }
        // 並び替えを実施
        return $query->orderBy('schedule_date', 'asc')->orderBy('ride_id', 'asc');
    }

    // 所要時間を取得
    public function getRequiredMinutes($rides)
    {
        // Builder / Collection / Paginator のいずれが来ても動くようにする分岐

        // ページネーション（LengthAwarePaginator）が渡ってきた場合
        if ($rides instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            // Paginator の「中身の Collection」だけを取り出して処理する
            // ページネーション構造自体は壊さない
            $rides->getCollection()->each(function ($ride) {
                $this->calcMinutesForRoute($ride);
            });
            // 加工済みの Paginator をそのまま返す
            return $rides;
        }
        // クエリビルダ（Builder）が渡ってきた場合
        $rides = $rides instanceof \Illuminate\Database\Eloquent\Builder ? $rides->get() : $rides;
        $rides->each(function ($ride) {
            $this->calcMinutesForRoute($ride);
        });
        // 加工済みの Collection を返す
        return $rides;
    }

    // 所要時間の計算処理
    private function calcMinutesForRoute($ride)
    {
        // ルート詳細を取得
        $details = $ride->ride_details->sortBy('stop_order')->values();
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
        $ride->setRelation('ride_details', $details);
    }
}
<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="p-6 bg-yellow-100 rounded-lg mt-4 shadow-sm">
        <dl class="space-y-4">
            <x-admin.route-detail.info-div label="ルート区分" :value="$route->route_type->route_type" />
            <x-admin.route-detail.info-div label="車両種別" :value="$route->vehicle_category->vehicle_category" />
            <x-admin.route-detail.info-div label="ルート名" :value="$route->route_name" />
        </dl>
    </div>
    <div class="flex mt-3">
        <button id="add_route_detail_btn" type="button" class="btn bg-green-600 text-white p-3 ml-auto"><i class="las la-plus la-lg mr-1"></i>ルート詳細追加</button>
    </div>
    <form method="POST" action="{{ route('route_detail_update.update') }}" id="route_detail_update_form">
        @csrf
        <div id="route_detail_wrapper">
            @foreach ($route->route_details as $index => $route_detail)
                <div class="route_detail_div p-5 bg-white rounded-lg mt-3">
                    <div class="flex justify-between items-center">
                        <p class="text-base">ルート詳細 {{ $index + 1 }}</p>
                    </div>
                    <div class="flex flex-row text-xs gap-5 mt-3">
                        <div class="flex flex-row w-6/12">
                            <x-admin.route-detail.select label="乗降場所" name="boarding_location_id" :index="$index" :items="$boarding_locations" optionValue="boarding_location_id" optionText="location_name" :value="$route_detail->boarding_location_id" />
                            <x-admin.route-detail.input type="tel" label="停車順番" name="stop_order" :index="$index" :value="$route_detail->stop_order" />
                            <x-admin.route-detail.input type="time" label="出発時刻" name="departure_time" :index="$index" :value="CarbonImmutable::parse($route_detail->departure_time)->format('H:i')" />
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($route->route_details->isEmpty())
                <div class="detail_div p-5 bg-white rounded-lg mt-3">
                    <div class="flex justify-between items-center">
                        <p class="text-base">ルート詳細</p>
                    </div>
                    <div class="flex flex-row text-xs gap-5 mt-3">
                        <div class="flex flex-row w-3/12">
                            <x-admin.route-detail.select label="乗降場所" name="boarding_location_id" :items="$boarding_locations" />
                            <x-admin.route-detail.input type="tel" label="停車順番" name="stop_order" :index="$index" :value="null" />
                            <x-admin.route-detail.input type="time" label="出発時刻" name="departure_time" :index="$index" :value="null" />
                        </div>
                        
                    </div>
                </div>
            @endif
        </div>
        <input type="hidden" id="route_id" name="route_id" value="{{ $route->route_id }}">
        <button type="button" id="route_detail_update_enter" class="btn bg-btn-enter p-3 text-white w-56 mt-3"><i class="las la-check la-lg mr-1"></i>設定</button>
    </form>
</x-app-layout>
@vite(['resources/js/admin/route_detail/route_detail.js'])
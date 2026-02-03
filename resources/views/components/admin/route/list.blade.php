<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="route_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">有効/無効</th>
                    <th class="font-thin py-1 px-2 text-center">ルート区分</th>
                    <th class="font-thin py-1 px-2 text-center">車両種別</th>
                    <th class="font-thin py-1 px-2 text-center">ルート名</th>
                    <th class="font-thin py-1 px-2 text-center">乗降場所数</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($routes as $route)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group @if(!$route->is_active) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                <button type="button" class="btn route_toggle_components_btn bg-btn-open text-white py-1 px-2">ルート詳細を表示</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$route->is_active" label1="有効" label0="無効" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $route->route_type->route_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ $route->vehicle_category->vehicle_category }}</td>
                        <td class="py-1 px-2 border">{{ $route->route_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($route->route_details->count()) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($route->sort_order) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($route->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($route->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                    <tr class="route_detail_components hidden">
                        <td colspan="7" class="p-0">
                            <div class="inline-block">
                                <table class="text-xs border border-gray-300">
                                    <thead>
                                        <tr class="text-left bg-black text-white">
                                            <th class="font-thin py-1 px-2 border border-black text-center">場所名</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">場所名メモ</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">停車順番</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">出発時刻</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">次の地点まで</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @foreach($route->route_details as $route_detail)
                                            <tr class="hover:bg-theme-sub">
                                                <td class="py-1 px-2 border border-black">{{ $route_detail->boarding_location->location_name }}</td>
                                                <td class="py-1 px-2 border border-black">{{ $route_detail->boarding_location->location_memo }}</td>
                                                <td class="py-1 px-2 border border-black text-right">{{ $route_detail->stop_order }}</td>
                                                <td class="py-1 px-2 border border-black text-center">{{ CarbonImmutable::parse($route_detail->departure_time)->format('H:i') }}</td>
                                                <td class="py-1 px-2 border border-black text-center">
                                                    @if($route_detail->required_minutes !== null)
                                                        <span class="inline-flex justify-center items-center bg-blue-100 text-blue-700 border border-blue-400 rounded px-2 py-0.5 text-xs w-12 tabular-nums">
                                                            {{ $route_detail->required_minutes }} 分
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="ride_schedule_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">運行状況</th>
                    <th class="font-thin py-1 px-2 text-center">ルート区分</th>
                    <th class="font-thin py-1 px-2 text-center">送迎日</th>
                    <th class="font-thin py-1 px-2 text-center">ドライバー</th>
                    <th class="font-thin py-1 px-2 text-center">使用車両名</th>
                    <th class="font-thin py-1 px-2 text-center">送迎メモ</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($routeSchedules as $route_schedule)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$route_schedule->is_active" label1="運行決定" label0="運行未定" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $route_schedule->route_type->route_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($route_schedule->schedule_date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $route_schedule->user->full_name }}</td>
                        <td class="py-1 px-2 border">{{ $route_schedule->vehicle->vehicle_name }}</td>
                        <td class="py-1 px-2 border">{{ $route_schedule->route_schedule_memo }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($route_schedule->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($route_schedule->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
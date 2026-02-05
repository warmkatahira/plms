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
                    <th class="font-thin py-1 px-2 text-center">送迎可能人数</th>
                    <th class="font-thin py-1 px-2 text-center">送迎予定人数</th>
                    <th class="font-thin py-1 px-2 text-center">残り座席数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($rides as $ride)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                <button type="button" class="btn route_delete_enter bg-btn-cancel text-white py-1 px-2" data-route-schedule-id="{{ $ride->ride_id }}">削除</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$ride->is_active" label1="運行決定" label0="運行未定" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $ride->route_type->route_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($ride->schedule_date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $ride->user->full_name }}</td>
                        <td class="py-1 px-2 border">{{ $ride->vehicle->vehicle_name }}</td>
                        <td class="py-1 px-2 border">{{ $ride->ride_memo }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($ride->vehicle->vehicle_capacity) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format(10) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($ride->vehicle->vehicle_capacity - 10) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($ride->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($ride->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form method="POST" action="{{ route('route_delete.delete') }}" id="route_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="ride_id" name="route_id">
</form>
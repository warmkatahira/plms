<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="ride_schedule_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">運行状況</th>
                    <th class="font-thin py-1 px-2 text-center">ルート区分</th>
                    <th class="font-thin py-1 px-2 text-center">ルート名</th>
                    <th class="font-thin py-1 px-2 text-center">送迎日</th>
                    <th class="font-thin py-1 px-2 text-center">ドライバー</th>
                    <th class="font-thin py-1 px-2 text-center">使用車両名</th>
                    <th class="font-thin py-1 px-2 text-center">送迎メモ</th>
                    <th class="font-thin py-1 px-2 text-center">定員</th>
                    <th class="font-thin py-1 px-2 text-center">利用者数</th>
                    <th class="font-thin py-1 px-2 text-center">残り座席数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($rides as $ride)
                    @php
                        // 変数を初期化
                        $seats_remaining_class = '';
                        // 残り座席数
                        $seats_remaining = $ride->vehicle?->vehicle_capacity - $ride->ride_details->sum(fn($detail) => $detail->ride_users->count());
                        // 残り座席数がマイナスの場合
                        if($seats_remaining < 0){
                            $seats_remaining_class = 'text-red-500';
                        }
                        // 残り座席数がプラスの場合
                        if($seats_remaining > 0){
                            $seats_remaining_class = 'text-blue-500';
                        }
                    @endphp
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <button type="button" class="btn ride_toggle_components_btn bg-btn-open text-white py-1 px-2">送迎詳細を表示</button>
                                <div class="dropdown-operation">
                                    <button class="dropdown-operation-btn"><i class="las la-ellipsis-v la-lg"></i></button>
                                    <div class="dropdown-operation-content">
                                        <a href="" class="dropdown-operation-content-element"><i class="las la-edit la-lg mr-1"></i>送迎予定を更新</a>
                                        <a href="" class="dropdown-operation-content-element"><i class="las la-edit la-lg mr-1"></i>送迎詳細を更新</a>
                                        <button type="button" class="dropdown-operation-content-element route_delete_enter" data-ride-id="{{ $ride->ride_id }}"><i class="las la-trash la-lg mr-1"></i>削除</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$ride->is_active" label1="運行決定" label0="運行未定" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $ride->route_type->route_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ $ride->route_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($ride->schedule_date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $ride->user?->full_name }}</td>
                        <td class="py-1 px-2 border">{{ $ride->vehicle?->vehicle_name }}</td>
                        <td class="py-1 px-2 border">{{ $ride->ride_memo }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($ride->vehicle?->vehicle_capacity) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($ride->ride_details->sum(fn($detail) => $detail->ride_users->count())) }}</td>
                        <td class="py-1 px-2 border text-right {{ $seats_remaining_class }}">{{ number_format($seats_remaining) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($ride->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($ride->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                    <tr class="ride_detail_components hidden">
                        <td colspan="11" class="p-0">
                            <div class="inline-block">
                                <table class="text-xs border border-gray-300 mb-3">
                                    <thead>
                                        <tr class="text-left bg-black text-white whitespace-nowrap">
                                            <th class="font-thin py-1 px-2 border border-black text-center">場所名</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">場所名メモ</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">停車順番</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">着 → 発</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">次の地点まで</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">利用者数</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">利用者</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @foreach($ride->ride_details as $ride_detail)
                                            @php
                                                // 出発時刻を取得
                                                $dep = $ride_detail->departure_time ? CarbonImmutable::parse($ride_detail->departure_time)->format('H:i') : null;
                                                // 到着時刻を取得
                                                $arr = $ride_detail->arrival_time ? CarbonImmutable::parse($ride_detail->arrival_time)->format('H:i') : null;
                                            @endphp
                                            <tr class="hover:bg-theme-sub whitespace-nowrap">
                                                <td class="py-1 px-2 border border-black">{{ $ride_detail->location_name }}</td>
                                                <td class="py-1 px-2 border border-black">{{ $ride_detail->location_memo }}</td>
                                                <td class="py-1 px-2 border border-black text-right">{{ $ride_detail->stop_order }}</td>
                                                <td class="py-1 px-2 border border-black text-center">
                                                    @if($arr && $dep)
                                                        <span class="text-orange-700 font-medium">{{ $arr }} 着</span>
                                                        <span class="mx-1">→</span>
                                                        <span class="text-blue-700 font-medium">{{ $dep }} 発</span>
                                                    @elseif($arr)
                                                        <span class="text-orange-700 font-medium">{{ $arr }} 着</span>
                                                    @elseif($dep)
                                                        <span class="text-blue-700 font-medium">{{ $dep }} 発</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-1 px-2 border border-black text-center">
                                                    @if($ride_detail->required_minutes !== null)
                                                        <span class="inline-flex justify-center items-center bg-blue-100 text-blue-700 border border-blue-400 rounded px-2 py-0.5 text-xs w-12 tabular-nums">
                                                            {{ $ride_detail->required_minutes }} 分
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="py-1 px-2 border border-black text-right">{{ number_format($ride_detail->ride_users->count()) }}</td>
                                                <td class="py-1 px-2 border border-black">
                                                    <div class="flex flex-row flex-wrap gap-2 justify-start">
                                                        @foreach($ride_detail->ride_users as $ride_user)
                                                            <span class="inline-flex items-center gap-1 bg-gray-200 border border-gray-300 rounded-full px-2 py-0.5 text-xs whitespace-nowrap writing-horizontal" style="flex: 0 0 calc(12% - 0.5rem);">
                                                                <span class="tippy_ride_user cursor-default text-center w-full" data-full-name="{{ $ride_user->user->full_name }}">
                                                                    {{ $ride_user->user->last_name }}
                                                                </span>
                                                            </span>
                                                        @endforeach
                                                    </div>
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
<form method="POST" action="{{ route('route_delete.delete') }}" id="route_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="ride_id" name="route_id">
</form>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="vehicle_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">利用可否</th>
                    <th class="font-thin py-1 px-2 text-center">車両区分</th>
                    <th class="font-thin py-1 px-2 text-center">車両種別</th>
                    <th class="font-thin py-1 px-2 text-center">所有者</th>
                    <th class="font-thin py-1 px-2 text-center">車両名</th>
                    <th class="font-thin py-1 px-2 text-center">車両色</th>
                    <th class="font-thin py-1 px-2 text-center">車両ナンバー</th>
                    <th class="font-thin py-1 px-2 text-center">送迎可能人数</th>
                    <th class="font-thin py-1 px-2 text-center">車両メモ</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($vehicles as $vehicle)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group @if(!$vehicle->is_active) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('vehicle_update.index', ['vehicle_id' => $vehicle->vehicle_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$vehicle->is_active" label1="利用可" label0="利用不可" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $vehicle->vehicle_type->vehicle_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ $vehicle->vehicle_category->vehicle_category }}</td>
                        <td class="py-1 px-2 border text-center">{{ $vehicle->owner }}</td>
                        <td class="py-1 px-2 border">{{ $vehicle->vehicle_name }}</td>
                        <td class="py-1 px-2 border">{{ $vehicle->vehicle_color }}</td>
                        <td class="py-1 px-2 border text-center">{{ $vehicle->vehicle_number }}</td>
                        <td class="py-1 px-2 border text-right">{{ $vehicle->vehicle_capacity }}</td>
                        <td class="py-1 px-2 border text-right">{{ $vehicle->vehicle_memo }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($vehicle->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($vehicle->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
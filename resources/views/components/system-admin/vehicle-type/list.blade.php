<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="vehicle_type_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">車両区分</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">車両数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($vehicleTypes as $vehicle_type)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">{{ $vehicle_type->vehicle_type }}</td>
                        <td class="py-1 px-2 border text-right">{{ $vehicle_type->sort_order }}</td>
                        <td class="py-1 px-2 border text-right">{{ $vehicle_type->vehicles->count() }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($vehicle_type->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($vehicle_type->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
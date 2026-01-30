<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="boarding_location_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">利用可否</th>
                    <th class="font-thin py-1 px-2 text-center">場所名</th>
                    <th class="font-thin py-1 px-2 text-center">場所メモ</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($boardingLocations as $boarding_location)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group @if(!$boarding_location->is_active) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('boarding_location_update.index', ['boarding_location_id' => $boarding_location->boarding_location_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$boarding_location->is_active" label1="利用可" label0="利用不可" />
                        </td>
                        <td class="py-1 px-2 border">{{ $boarding_location->location_name }}</td>
                        <td class="py-1 px-2 border">{{ $boarding_location->location_memo }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($boarding_location->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($boarding_location->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="route_type_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">ルート区分</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">ルート数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($routeTypes as $route_type)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">{{ $route_type->route_type }}</td>
                        <td class="py-1 px-2 border text-right">{{ $route_type->sort_order }}</td>
                        <td class="py-1 px-2 border text-right">{{ $route_type->routes->count() }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($route_type->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($route_type->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="shipping_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">ランク</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">出荷数</th>
                    <th class="font-thin py-1 px-2 text-center">構成比</th>
                    <th class="font-thin py-1 px-2 text-center">累積構成比</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($items as $item)
                    <tr @class([
                        'text-left cursor-default whitespace-nowrap hover:bg-theme-sub group',
                        'bg-green-100' => $item->rank === 'A',
                        'bg-yellow-100' => $item->rank === 'B',
                        'bg-blue-100' => $item->rank === 'C',
                    ])>
                        <td class="py-1 px-2 border text-center">{{ $item->rank }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($item->total_ship_quantity) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($item->ratio, 2) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($item->cumulative_ratio, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
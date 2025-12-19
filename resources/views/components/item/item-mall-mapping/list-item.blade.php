<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_mall_mapping_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ1</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ2</th>
                    @foreach($malls as $mall)
                        <th class="font-thin py-1 px-2 text-center">{{ $mall->mall_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($items as $item)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <img class="item_image mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$item->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border">{{ $item->item_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_name }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_category_1 }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_category_2 }}</td>
                        @foreach($malls as $mall)
                            <td class="py-1 px-2 border text-center text-btn-enter">
                                @if($item->{'mall_id_' . $mall->mall_id}) <i class="las la-check-circle la-2x"></i> @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
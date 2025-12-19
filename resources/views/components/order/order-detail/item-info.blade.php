<div>
    <div class="flex flex-row gap-5 items-center border-b pb-2 mb-4">
        <p class="text-base font-semibold">商品情報</p>
        <div class="flex flex-row">
            <p class="bg-black text-white px-5 py-1">合計注文数</p>
            <p class="bg-theme-sub px-5 py-1">{{ number_format($order->getTotalOrderQuantity()) }}</p>
        </div>
        <div class="flex flex-row">
            <p class="bg-black text-white px-5 py-1">合計出荷数</p>
            <p class="bg-theme-sub px-5 py-1">{{ number_format($order->getTotalShipQuantity()) }}</p>
        </div>
        <div class="flex flex-row">
            <p class="bg-black text-white px-5 py-1">合計未引当数</p>
            <p class="bg-theme-sub px-5 py-1">{{ number_format($order->getTotalUnallocatedQuantity()) }}</p>
        </div>
    </div>
    <div class="disable_scrollbar flex flex-grow overflow-scroll">
        <div class="order_detail_item_info_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        <th class="font-thin py-1 px-2 text-center">操作</th>
                        <th class="font-thin py-1 px-2 text-center">商品画像</th>
                        <th class="font-thin py-1 px-2 text-center">セット商品</th>
                        <th class="font-thin py-1 px-2 text-center">商品引当</th>
                        <th class="font-thin py-1 px-2 text-center">在庫引当</th>
                        <th class="font-thin py-1 px-2 text-center">自動処理追加</th>
                        <th class="font-thin py-1 px-2 text-center">商品コード</th>
                        <th class="font-thin py-1 px-2 text-center">商品名</th>
                        <th class="font-thin py-1 px-2 text-center">注文数</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            <td class="py-1 px-2 border"><button type="button" class="btn toggle_components_btn bg-btn-open text-white py-1 px-2"><i class="las la-plus"></i></button></td>
                            <td class="py-1 px-2 border">
                                @if($order_item->allocated_set_item_id)
                                    <img src="{{ asset('storage/set_item_images/'.$order_item->set_item?->set_item_image_file_name) }}" class="item_image mx-auto image_fade_in_modal_open">
                                @else
                                    <img src="{{ asset('storage/item_images/'.$order_item->item?->item_image_file_name) }}" class="item_image mx-auto image_fade_in_modal_open">
                                @endif
                            </td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->allocated_set_item_id ? 1 : 0) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_item_allocated) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_stock_allocated) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_auto_process_add) !!}</td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $order_item->order_item_code }}
                                <x-clipboard-copy-btn :value="$order_item->order_item_code" label="商品コード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $order_item->order_item_name }}
                                <x-clipboard-copy-btn :value="$order_item->order_item_name" label="商品名" />
                            </td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->order_quantity) }}</td>
                        </tr>
                        <tr class="order_item_components hidden">
                            <td colspan="9" class="p-0">
                                <table class="w-full text-xs border-t border-gray-300">
                                    <thead>
                                        <tr class="text-left bg-gray-300">
                                            <th class="font-thin py-1 px-2 border border-black text-center">商品画像</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">在庫引当</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">未引当数</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">商品コード</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">商品JANコード</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">商品名</th>
                                            <th class="font-thin py-1 px-2 border border-black text-center">出荷数</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-theme-sub">
                                        @foreach($order_item->order_item_components as $order_item_component)
                                            <tr>
                                                <td class="py-1 px-2 border border-black">
                                                    <img src="{{ asset('storage/item_images/'.$order_item_component->item->item_image_file_name) }}" class="item_image mx-auto image_fade_in_modal_open">
                                                </td>
                                                <td class="py-1 px-2 border border-black text-center">{!! displayCheckIfTrue($order_item_component->is_stock_allocated) !!}</td>
                                                <td class="py-1 px-2 border border-black text-right">{{ number_format($order_item_component->unallocated_quantity) }}</td>
                                                <td class="py-1 px-2 border border-black relative group/clipboard">
                                                    {{ $order_item_component->item_code_snapshot }}
                                                    <x-clipboard-copy-btn :value="$order_item_component->item_code_snapshot" label="商品コード" />
                                                </td>
                                                <td class="py-1 px-2 border border-black relative group/clipboard">
                                                    {{ $order_item_component->item_jan_code_snapshot }}
                                                    <x-clipboard-copy-btn :value="$order_item_component->item_jan_code_snapshot" label="商品JANコード" />
                                                </td>
                                                <td class="py-1 px-2 border border-black relative group/clipboard">
                                                    {{ $order_item_component->item_name_snapshot }}
                                                    <x-clipboard-copy-btn :value="$order_item_component->item_name_snapshot" label="商品名" />
                                                </td>
                                                <td class="py-1 px-2 border border-black text-right">{{ number_format($order_item_component->ship_quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
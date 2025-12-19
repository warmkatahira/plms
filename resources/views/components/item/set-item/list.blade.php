<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="set_item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">セット商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">セット商品ID</th>
                    <th class="font-thin py-1 px-2 text-center">セット商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">セット商品名</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($setItems as $set_item)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <button type="button" class="btn toggle_components_btn bg-btn-open text-white py-1 px-2"><i class="las la-plus"></i></button>
                                <a href="{{ route('set_item_update.index', ['set_item_id' => $set_item->set_item_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                <button type="button" class="btn set_item_delete_enter bg-btn-cancel text-white py-1 px-2" data-set-item-id="{{ $set_item->set_item_id }}">削除</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">
                            <img class="item_image mx-auto image_fade_in_modal_open" src="{{ asset('storage/set_item_images/'.$set_item->set_item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border text-right relative group/clipboard">
                            {{ number_format($set_item->set_item_id) }}
                            <x-clipboard-copy-btn :value="$set_item->set_item_id" label="セット商品ID" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $set_item->set_item_code }}
                            <x-clipboard-copy-btn :value="$set_item->set_item_code" label="セット商品コード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $set_item->set_item_name }}
                            <x-clipboard-copy-btn :value="$set_item->set_item_name" label="セット商品名" />
                        </td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($set_item->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                    <tr class="order_item_components hidden">
                        <td colspan="6" class="p-0">
                            <table class="w-full text-xs border-t border-gray-300">
                                <thead>
                                    <tr class="text-left bg-gray-300">
                                        <th class="font-thin py-1 px-2 border border-black text-center">商品画像</th>
                                        <th class="font-thin py-1 px-2 border border-black text-center">商品コード</th>
                                        <th class="font-thin py-1 px-2 border border-black text-center">商品JANコード</th>
                                        <th class="font-thin py-1 px-2 border border-black text-center">商品名</th>
                                        <th class="font-thin py-1 px-2 border border-black text-center">構成数</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-theme-sub">
                                    @foreach($set_item->set_item_details as $set_item_detail)
                                        <tr>
                                            <td class="py-1 px-2 border border-black">
                                                <img src="{{ asset('storage/item_images/'.$set_item_detail->item->item_image_file_name) }}" class="item_image mx-auto image_fade_in_modal_open">
                                            </td>
                                            <td class="py-1 px-2 border border-black text-center">{{ $set_item_detail->item->item_code }}</td>
                                            <td class="py-1 px-2 border border-black text-center">{{ $set_item_detail->item->item_jan_code }}</td>
                                            <td class="py-1 px-2 border border-black">{{ $set_item_detail->item->item_name }}</td>
                                            <td class="py-1 px-2 border border-black text-right">{{ number_format($set_item_detail->component_quantity) }}</td>
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
<form method="POST" action="{{ route('set_item_delete.delete') }}" id="set_item_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="set_item_id" name="set_item_id">
</form>
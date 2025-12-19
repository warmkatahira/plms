<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="order_category_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">受注区分名</th>
                    <th class="font-thin py-1 px-2 text-center">モール</th>
                    <th class="font-thin py-1 px-2 text-center">荷送人名</th>
                    <th class="font-thin py-1 px-2 text-center">荷札品名1</th>
                    <th class="font-thin py-1 px-2 text-center">荷札品名2</th>
                    <th class="font-thin py-1 px-2 text-center">荷札品名3</th>
                    <th class="font-thin py-1 px-2 text-center">荷札品名4</th>
                    <th class="font-thin py-1 px-2 text-center">荷札品名5</th>
                    <th class="font-thin py-1 px-2 text-center">API設定</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($orderCategories as $order_category)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('order_category_update.index', ['order_category_id' => $order_category->order_category_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $order_category->order_category_name }}</td>
                        <td class="py-1 px-2 border text-center align-middle">
                            <img src="{{ asset('storage/mall_images/'.$order_category->mall->mall_image_file_name) }}" class="mall_image inline-block">
                        </td>
                        <td class="py-1 px-2 border">{{ $order_category->shipper->shipper_name }}</td>
                        <td class="py-1 px-2 border">{{ $order_category->nifuda_product_name_1 }}</td>
                        <td class="py-1 px-2 border">{{ $order_category->nifuda_product_name_2 }}</td>
                        <td class="py-1 px-2 border">{{ $order_category->nifuda_product_name_3 }}</td>
                        <td class="py-1 px-2 border">{{ $order_category->nifuda_product_name_4 }}</td>
                        <td class="py-1 px-2 border">{{ $order_category->nifuda_product_name_5 }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order_category->api_setting_text }}</td>
                        <td class="py-1 px-2 border text-right">{{ $order_category->sort_order }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
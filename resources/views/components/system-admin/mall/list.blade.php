<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="mall_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">モールロゴ</th>
                    <th class="font-thin py-1 px-2 text-center">モール名</th>
                    <th class="font-thin py-1 px-2 text-center">受注区分数</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($malls as $mall)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('mall_update.index', ['mall_id' => $mall->mall_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <img class="image_fade_in_modal_open mall_image inline-block" src="{{ asset('storage/mall_images/'.$mall->mall_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border">{{ $mall->mall_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($mall->order_categories->count()) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
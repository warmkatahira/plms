<div id="order_import_pattern_select_modal" class="order_import_pattern_select_modal_close fixed hidden z-40 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-[900px]">
        <div class="flex justify-between items-center bg-theme-main rounded-t-md px-4 py-2">
            <p>受注取込パターンを選択して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('order_import_pattern.import') }}" id="order_import_pattern_form" enctype="multipart/form-data">
                @csrf
                <div class="disable_scrollbar flex flex-grow overflow-scroll">
                    <div class="order_import_pattern_select_modal_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
                        <table class="text-xs">
                            <thead>
                                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 z-10">
                                    <th class="font-thin py-1 px-2 text-center">選択</th>
                                    <th class="font-thin py-1 px-2 text-center">受注区分</th>
                                    <th class="font-thin py-1 px-2 text-center">パターン名</th>
                                    <th class="font-thin py-1 px-2 text-center">説明</th>
                                    <th class="font-thin py-1 px-2 text-center">カラム取得方法</th>
                                    <th class="font-thin py-1 px-2 text-center">更新日時</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($orderImportPatterns as $order_import_pattern)
                                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                                        <td class="py-1 px-2 border text-center">
                                            <input type="radio" name="order_import_pattern_id" class="order_import_pattern_select" value="{{ $order_import_pattern->order_import_pattern_id }}">
                                        </td>
                                        <td class="py-1 px-2 border relative group/clipboard">{{ $order_import_pattern->order_category->order_category_name }}</td>
                                        <td class="py-1 px-2 border relative group/clipboard">{{ $order_import_pattern->pattern_name }}</td>
                                        <td class="py-1 px-2 border relative group/clipboard">{{ $order_import_pattern->pattern_description }}</td>
                                        <td class="py-1 px-2 border text-center">{{ OrderImportPatternEnum::getColumnGetMethodJp($order_import_pattern->column_get_method) }}</td>
                                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order_import_pattern->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-between mt-10">
                    <div class="flex select_file dropdown-content-element">
                        <label id="order_import_pattern_file_select_label" class="text-center cursor-pointer btn bg-btn-enter p-3 text-white w-56 hidden">
                            <i class="las la-check la-lg mr-1"></i>
                            ファイル選択
                            <input type="file" id="order_import_pattern_form" name="select_file" class="hidden">
                        </label>
                    </div>
                    <button type="button" class="order_import_pattern_select_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="order_import_pattern_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
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
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('order_import_pattern_update.index', ['order_import_pattern_id' => $order_import_pattern->order_import_pattern_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                <button type="button" class="btn order_import_pattern_delete_enter bg-btn-cancel text-white py-1 px-2" data-order-import-pattern-id="{{ $order_import_pattern->order_import_pattern_id }}">削除</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $order_import_pattern->order_category->order_category_name }}</td>
                        <td class="py-1 px-2 border pattern_name">{{ $order_import_pattern->pattern_name }}</td>
                        <td class="py-1 px-2 border">{{ $order_import_pattern->pattern_description }}</td>
                        <td class="py-1 px-2 border text-center">{{ OrderImportPatternEnum::getColumnGetMethodJp($order_import_pattern->column_get_method) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($order_import_pattern->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form method="POST" action="{{ route('order_import_pattern_delete.delete') }}" id="order_import_pattern_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="order_import_pattern_id" name="order_import_pattern_id">
</form>
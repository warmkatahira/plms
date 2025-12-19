<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.input type="text" label="商品コード" id="search_item_code" name="search_item_code" />
        <x-search.input type="text" label="商品JANコード" id="search_item_jan_code" name="search_item_jan_code" />
        <x-search.input type="text" label="商品名" id="search_item_name" name="search_item_name" />
        <x-search.input type="text" label="商品カテゴリ1" id="search_item_category_1" name="search_item_category_1" />
        <x-search.input type="text" label="商品カテゴリ2" id="search_item_category_2" name="search_item_category_2" />
        <x-search.select-boolean label="在庫管理" id="search_is_stock_managed" name="search_is_stock_managed" label0="無効" label1="有効" />
        <x-search.select-boolean label="出荷検品要否" id="search_is_shipping_inspection_required" name="search_is_shipping_inspection_required" label0="非" label1="要" />
        <x-search.select-boolean label="納品書表示" id="search_is_hide_on_delivery_note" name="search_is_hide_on_delivery_note" label0="表示" label1="非表示" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>

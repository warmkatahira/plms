<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.date-period type="date" fromId="search_shipping_date_from" fromName="search_shipping_date_from" toId="search_shipping_date_to" toName="search_shipping_date_to" label="出荷日" />
        <x-search.select label="受注区分" id="search_order_category_id" name="search_order_category_id" :selectItems="$orderCategories" optionValue="order_category_id" optionText="order_category_name" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <input type="hidden" id="disp_type" name="disp_type" value="{{ session('disp_type') }}">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>
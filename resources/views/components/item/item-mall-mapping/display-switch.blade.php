<div class="flex flex-row ml-5 mt-1 items-start divide-x divide-black border border-black">
    <!-- 単品商品表示 -->
    <a href="{{ route('item_mall_mapping.index_item') }}" class="btn display_switch tippy_display_by_item px-2 py-1 {{ request()->routeIs('item_mall_mapping.index_item') ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-tshirt la-2x"></i>
    </a>
    <!-- セット商品表示 -->
    <a href="{{ route('item_mall_mapping.index_set_item') }}" class="btn display_switch tippy_display_by_set_item px-2 py-1 {{ request()->routeIs('item_mall_mapping.index_set_item') ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-gift la-2x"></i>
    </a>
</div>
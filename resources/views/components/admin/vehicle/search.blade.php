<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.select-boolean label="利用可否" id="search_is_active" name="search_is_active" label1="利用可" label0="利用不可" />
        <x-search.select label="車両区分" id="search_vehicle_type_id" name="search_vehicle_type_id" :selectItems="$vehicleTypes" optionValue="vehicle_type_id" optionText="vehicle_type" />
        <x-search.select label="車両種別" id="search_vehicle_category_id" name="search_vehicle_category_id" :selectItems="$vehicleCategories" optionValue="vehicle_category_id" optionText="vehicle_category" />
        <x-search.select-array label="定員" id="search_vehicle_capacity" name="search_vehicle_capacity" :items="$vehicleCapacityConditions" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>

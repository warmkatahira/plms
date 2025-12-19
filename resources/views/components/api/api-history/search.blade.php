<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.select label="モール" id="search_mall_id" name="search_mall_id" :selectItems="$malls" optionValue="mall_id" optionText="mall_name" />
        <x-search.select label="実行内容" id="search_api_action_id" name="search_api_action_id" :selectItems="$apiActions" optionValue="api_action_id" optionText="api_action_name" />
        <x-search.select label="ステータス" id="search_api_status_id" name="search_api_status_id" :selectItems="$apiStatuses" optionValue="api_status_id" optionText="api_status_name" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>

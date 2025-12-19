<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="{{ route('shipping_actual_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>伝票番号一括登録ダウンロード</a>
            <a href="{{ route('order_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>受注ダウンロード</a>
        </div>
    </div>
</div>
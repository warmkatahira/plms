<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="{{ route('employee_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>ダウンロード</a>
            @can('admin_check')
                <a href="{{ route('employee_create.index') }}" class="dropdown-content-element"><i class="las la-pen la-lg mr-1"></i>従業員追加(入力)</a>
            @endcan
        </div>
    </div>
</div>
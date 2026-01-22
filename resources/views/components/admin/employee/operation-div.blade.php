<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="{{ route('employee_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>ダウンロード</a>
            @can('admin_check')
                <a href="{{ route('employee_create.index') }}" class="dropdown-content-element"><i class="las la-pen la-lg mr-1"></i>従業員追加(入力)</a>
                <form method="POST" action="{{ route('employee_create.import') }}" id="employee_create_import_form" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <div class="flex select_file dropdown-content-element">
                        <label class="text-xs cursor-pointer">
                            <i class="las la-upload la-lg mr-1"></i>従業員追加(取込)
                            <input type="file" name="select_file" class="hidden">
                        </label>
                    </div>
                </form>
                <form method="POST" action="{{ route('paid_leave_update.import') }}" id="paid_leave_update_import_form" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <div class="flex select_file dropdown-content-element">
                        <label class="text-xs cursor-pointer">
                            <i class="las la-upload la-lg mr-1"></i>有給情報更新(取込)
                            <input type="file" name="select_file" class="hidden">
                        </label>
                    </div>
                </form>
                <form method="POST" action="{{ route('statutory_leave_update.import') }}" id="statutory_leave_update_import_form" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <div class="flex select_file dropdown-content-element">
                        <label class="text-xs cursor-pointer">
                            <i class="las la-upload la-lg mr-1"></i>義務情報更新(取込)
                            <input type="file" name="select_file" class="hidden">
                        </label>
                    </div>
                </form>
            @endcan
        </div>
    </div>
</div>
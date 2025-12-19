<div id="order_import_api_modal" class="order_import_api_modal_close fixed hidden z-40 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-[900px]">
        <div class="flex justify-between items-center bg-theme-main rounded-t-md px-4 py-2">
            <p>取込する条件を指定して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('makeshop_order_import.api') }}" id="order_import_form_api">
                @csrf
                <div class="flex flex-col">
                    <p><i class="las la-money-bill la-lg mr-1"></i>入金日</p>
                    <div class="flex flex-row gap-5 items-center mt-2 ml-5">
                        <select id="payment_date" class="rounded-md text-sm">
                            <option value="today" selected>今日</option>
                            <option value="yesterday">昨日</option>
                            <option value="current_week">今週</option>
                            <option value="current_month">今月</option>
                            <option value="previous_month">前月</option>
                            <option value="custom">カスタム</option>
                        </select>
                        <input type="date" id="date_from" name="date_from" class="date_time_element text-sm px-5 rounded-md" value="{{ CarbonImmutable::now()->toDateString() }}">
                        <input type="time" id="time_from" name="time_from" class="date_time_element text-sm px-5 rounded-md" value="00:00">
                        <p>～</p>
                        <input type="date" id="date_to" name="date_to" class="date_time_element text-sm px-5 rounded-md" value="{{ CarbonImmutable::now()->toDateString() }}">
                        <input type="time" id="time_to" name="time_to" class="date_time_element text-sm px-5 rounded-md" value="23:59">
                    </div>
                </div>
                <div class="flex justify-between mt-10">
                    <button type="button" id="order_import_api_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>実行</button>
                    <button type="button" class="order_import_api_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>
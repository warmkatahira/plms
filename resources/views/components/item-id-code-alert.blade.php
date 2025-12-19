<div id="item_id_code_alert_modal" class="item_id_code_alert_modal_close hidden fixed z-40 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-40">
    <div class="relative top-20 mx-auto shadow-lg bg-white">
        <p class="text-xl text-red-500 text-center bg-theme-sub py-5"><i class="las la-exclamation-triangle la-lg mr-1"></i>{{ $title }}<i class="las la-exclamation-triangle la-lg ml-1"></i></p>
        <div class="mt-5">
            <div class="grid grid-cols-12 gap-5 pb-5 items-center">
                <div class="col-span-3">
                    <img id="error_item_image" class="w-48 h-48 mx-auto image_fade_in_modal_open" src="">
                </div>
                <div class="col-span-9 grid grid-cols-12 gap-2 pr-5">
                    <div class="col-span-12 grid grid-cols-12">
                        <p class="text-center text-xl col-span-4 py-1 bg-black text-white flex items-center justify-center">メッセージ</p>
                        <p id="error_message" class="text-center text-xl col-span-8 py-1 px-2 bg-theme-sub break-words"></p>
                    </div>
                    <div class="col-span-12 grid grid-cols-12">
                        <p class="text-center text-xl col-span-4 py-1 bg-black text-white flex items-center justify-center">商品識別コード</p>
                        <p id="error_item_id_code" class="text-center text-xl col-span-8 py-1 px-2 bg-theme-sub break-words"></p>
                    </div>
                    <div class="col-span-12 grid grid-cols-12">
                        <p class="text-center text-xl col-span-4 py-1 bg-black text-white flex items-center justify-center">商品名</p>
                        <p id="error_item_name" class="text-center text-xl col-span-8 py-1 px-2 bg-theme-sub break-words"></p>
                    </div>
                </div">
            </div>
            <input type="tel" id="item_id_code_alert_modal_focus_element" class="w-0 h-0 border-transparent focus:border-transparent focus:ring-0" autocomplete="off">
        </div>
        <div class="flex pb-5 pr-5">
            <button type="button" class="item_id_code_alert_modal_close mt-5 ml-auto w-40 px-5 py-2 bg-black text-white">閉じる</button>
        </div>
    </div>
</div>
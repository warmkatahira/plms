<div id="select_order_document_modal" class="select_order_document_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main rounded-t-md px-4 py-2">
            <p>作成する帳票を選択して下さい。</p>
        </div>
        <div class="p-10 bg-theme-body">
            <div class="flex flex-col items-center gap-5">
                <button type="button" id="kobetsu_picking_list" class="btn select_order_document bg-theme-main p-5 w-96">個別ピッキングリスト</button>
                <button type="button" id="delivery_note" class="btn select_order_document bg-theme-main p-5 w-96">納品書</button>
                <button type="button" id="nifuda" class="btn select_order_document bg-theme-main p-5 w-96">荷札データ</button>
            </div>
        </div>
    </div>
</div>
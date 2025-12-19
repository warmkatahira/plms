<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('item_update.update') }}" id="item_update_form" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
                <x-form.image-select label="商品画像" />
                <x-form.p label="商品コード" :value="$item->item_code" />
                <x-form.input type="text" label="商品JANコード" id="item_jan_code" name="item_jan_code" :value="$item->item_jan_code" tippy="tippy_item_jan_code" />
                <x-form.input type="text" label="商品名" id="item_name" name="item_name" :value="$item->item_name" required="true" />
                <x-form.input type="text" label="商品カテゴリ1" id="item_category_1" name="item_category_1" :value="$item->item_category_1" />
                <x-form.input type="text" label="商品カテゴリ2" id="item_category_2" name="item_category_2" :value="$item->item_category_2" />
                <x-form.select-boolean label="在庫管理" id="is_stock_managed" name="is_stock_managed" :value="$item->is_stock_managed" label0="無効" label1="有効" required="true" />
                <x-form.select-boolean label="出荷検品要否" id="is_shipping_inspection_required" name="is_shipping_inspection_required" :value="$item->is_shipping_inspection_required" label0="非" label1="要" required="true" />
                <x-form.select-boolean label="納品書表示" id="is_hide_on_delivery_note" name="is_hide_on_delivery_note" :value="$item->is_hide_on_delivery_note" label0="表示" label1="非表示" required="true" />
                <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$item->sort_order" required="true" />
            </div>
            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
            <button type="button" id="item_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
        <div class="bg-white border border-black self-start">
            <p class="bg-black text-white text-center py-3">商品画像</p>
            <div class="p-5">
                <img class="w-40 h-40" src="{{ asset('storage/item_images/'.$item->item_image_file_name) }}">
            </div>
        </div>
    </div>
</x-app-layout>
@vite(['resources/js/item/item/item.js'])
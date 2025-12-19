<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 mt-5">
        <form method="POST" action="{{ route('set_item_update.update') }}" id="set_item_update_form" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
                <x-form.image-select label="セット商品画像" />
                <x-form.p label="セット商品コード" :value="$set_item->set_item_code" />
                <x-form.input type="text" label="セット商品名" id="set_item_name" name="set_item_name" :value="$set_item->set_item_name" required="true" />
            </div>
            <input type="hidden" name="set_item_id" value="{{ $set_item->set_item_id }}">
            <button type="button" id="set_item_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
        <div class="bg-white border border-black self-start">
            <p class="bg-black text-white text-center py-3">セット商品画像</p>
            <div class="p-5">
                <img class="w-40 h-40" src="{{ asset('storage/set_item_images/'.$set_item->set_item_image_file_name) }}">
            </div>
        </div>
    </div>
</x-app-layout>
@vite(['resources/js/item/set_item/set_item.js'])
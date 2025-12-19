<x-app-layout>
    <div class="flex flex-row my-3">
        <x-item.item-mall-mapping.operation-div-set-item />
        <x-pagination :pages="$set_items" />
        <x-item.item-mall-mapping.display-switch />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-item.item-mall-mapping.search-set-item route="item_mall_mapping.index_set_item" />
        <x-item.item-mall-mapping.list-set-item :setItems="$set_items" :malls="$malls" />
    </div>
</x-app-layout>
@vite(['resources/js/item/item_mall_mapping/item_mall_mapping.js'])
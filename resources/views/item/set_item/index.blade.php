<x-app-layout>
    <div class="flex flex-row my-3">
        <x-item.set-item.operation-div />
        <x-pagination :pages="$set_items" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-item.set-item.search route="set_item.index" />
        <x-item.set-item.list :setItems="$set_items" />
    </div>
</x-app-layout>
@vite(['resources/js/item/set_item/set_item.js'])
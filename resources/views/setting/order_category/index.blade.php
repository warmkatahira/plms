<x-app-layout>
    <div class="flex flex-row my-3">
        <x-setting.order-category.operation-div />
    </div>
    <x-setting.order-category.list :orderCategories="$order_categories" />
</x-app-layout>
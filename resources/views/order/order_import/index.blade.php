<x-app-layout>
    <div class="flex flex-row my-3">
        <x-order.order-import.operation-div :orderImportPatterns="$order_import_patterns" />
        <x-pagination :pages="$order_import_histories" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-order.order-import.search route="order_import.index" />
        <x-order.order-import.list :orderImportHistories="$order_import_histories" />
    </div>
</x-app-layout>
<x-order.order-import.order-import-api-modal />
<x-order.order-import.order-import-pattern-select-modal :orderImportPatterns="$order_import_patterns" />
@vite(['resources/js/order/order_import/order_import.js'])
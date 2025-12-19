<x-app-layout>
    <div class="flex flex-row my-3">
        <x-setting.order-import-pattern.operation-div />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-setting.order-import-pattern.list :orderImportPatterns="$order_import_patterns" />
    </div>
</x-app-layout>
@vite(['resources/js/setting/order_import_pattern/order_import_pattern.js'])
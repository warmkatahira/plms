<x-app-layout>
    <x-page-back :url="route('order_import_pattern.index')" />
    <div class="my-5 flex flex-row gap-5">
        @include('components.setting.order-import-pattern.form', [
            'form_mode' => 'update',
            'order_import_pattern' => $order_import_pattern,
        ])
    </div>
</x-app-layout>
@vite(['resources/js/setting/order_import_pattern/order_import_pattern.js'])
<x-app-layout>
    <x-page-back :url="route('order_category.index')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.setting.order-category.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/setting/order_category/order_category.js'])
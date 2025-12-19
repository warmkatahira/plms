<x-app-layout>
    <x-page-back :url="route('mall.index')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.system-admin.mall.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/mall/mall.js'])
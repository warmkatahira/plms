<x-app-layout>
    <x-page-back :url="route('shipper.index')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.setting.shipper.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/setting/shipper/shipper.js'])
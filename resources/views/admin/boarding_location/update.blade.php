<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.admin.boarding-location.form', [
            'form_mode' => 'update',
            'boarding_location' => $boarding_location,
        ])
    </div>
</x-app-layout>
@vite(['resources/js/admin/boarding_location/boarding_location.js'])
<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.ride.ride-schedule.form', [
            'form_mode' => 'update',
            'ride' => $ride,
        ])
    </div>
</x-app-layout>
@vite(['resources/js/ride/ride_schedule/ride_schedule.js'])
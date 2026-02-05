<x-app-layout>
    <div class="flex flex-row my-3">
        <x-ride.ride-schedule.operation-div />
        <x-pagination :pages="$rides" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-ride.ride-schedule.search route="ride_schedule.index" />
        <x-ride.ride-schedule.list :rides="$rides" />
    </div>
</x-app-layout>
@vite(['resources/js/ride/ride_schedule/ride_schedule.js'])
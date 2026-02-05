<x-app-layout>
    <div class="flex flex-row my-3">
        <x-ride-schedule.ride-schedule.operation-div />
        <x-pagination :pages="$boarding_locations" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-ride-schedule.ride-schedule.search ride="ride_schedule.index" />
        <x-ride-schedule.ride-schedule.list :rideSchedules="$ride_schedules" />
    </div>
</x-app-layout>
@vite(['resources/js/ride_schedule/ride_schedule/ride_schedule.js'])
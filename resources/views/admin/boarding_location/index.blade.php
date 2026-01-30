<x-app-layout>
    <div class="flex flex-row my-3">
        <x-admin.boarding-location.operation-div />
        <x-pagination :pages="$boarding_locations" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-admin.boarding-location.search route="boarding_location.index" />
        <x-admin.boarding-location.list :boardingLocations="$boarding_locations" />
    </div>
</x-app-layout>
@vite(['resources/js/admin/boarding_location/boarding_location.js'])
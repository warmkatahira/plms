<x-app-layout>
    <div class="flex flex-row my-3">
        <x-admin.vehicle.operation-div />
        <x-pagination :pages="$vehicles" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-admin.vehicle.search route="vehicle.index" :vehicleTypes="$vehicle_types" :vehicleCategories="$vehicle_categories" :vehicleCapacityConditions="$vehicle_capacity_conditions" />
        <x-admin.vehicle.list :vehicles="$vehicles" />
    </div>
</x-app-layout>
@vite(['resources/js/admin/vehicle/vehicle.js'])
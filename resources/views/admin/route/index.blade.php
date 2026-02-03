<x-app-layout>
    <div class="flex flex-row my-3">
        <x-admin.route.operation-div />
        <x-pagination :pages="$routes" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-admin.route.search route="route.index" :routeTypes="$route_types" :vehicleCategories="$vehicle_categories" />
        <x-admin.route.list :routes="$routes" />
    </div>
</x-app-layout>
@vite(['resources/js/admin/route/route.js'])
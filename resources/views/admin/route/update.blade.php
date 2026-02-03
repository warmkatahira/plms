<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.admin.route.form', [
            'form_mode' => 'update',
            'route' => $route,
        ])
    </div>
</x-app-layout>
@vite(['resources/js/admin/route/route.js'])
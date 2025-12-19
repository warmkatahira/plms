<x-app-layout>
    <x-page-back :url="route('company.index')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.system-admin.company.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/company/company.js'])
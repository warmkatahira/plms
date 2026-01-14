<x-app-layout>
    <div class="flex flex-row my-3">
        <x-pagination :pages="$employee_import_histories" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-admin.employee-import-history.search route="employee_import_history.index" />
        <x-admin.employee-import-history.list :employee_import_histories="$employee_import_histories" />
    </div>
</x-app-layout>
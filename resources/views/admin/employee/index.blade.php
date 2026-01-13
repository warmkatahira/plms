<x-app-layout>
    <div class="flex flex-row my-3">
        <x-admin.employee.operation-div />
        <x-pagination :pages="$employees" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-admin.employee.search route="employee.index" :bases="$bases" />
        <x-admin.employee.list :employees="$employees" />
    </div>
</x-app-layout>
<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.company.operation-div />
    </div>
    <x-system-admin.company.list :companies="$companies" />
</x-app-layout>
<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.user.operation-div />
    </div>
    <x-system-admin.user.list :users="$users" />
</x-app-layout>
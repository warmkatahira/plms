<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.mall.operation-div />
    </div>
    <x-system-admin.mall.list :malls="$malls" />
</x-app-layout>
@vite(['resources/js/system_admin/mall/mall.js'])
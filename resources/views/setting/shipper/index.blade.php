<x-app-layout>
    <div class="flex flex-row my-3">
        <x-setting.shipper.operation-div />
    </div>
    <x-setting.shipper.list :shippers="$shippers" />
</x-app-layout>
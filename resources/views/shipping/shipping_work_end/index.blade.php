<x-app-layout>
    <div class="flex flex-row gap-5 mt-5">
        <x-shipping.shipping-work-end.list :shippingGroups="$shipping_groups" />
    </div>
</x-app-layout>
@vite(['resources/js/shipping/shipping_work_end/shipping_work_end.js'])
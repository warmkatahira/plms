<x-app-layout>
    <div class="flex flex-row my-3">
        <x-pagination :pages="$shipping_work_end_histories" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.shipping-work-end-history.search route="shipping_work_end_history.index" />
        <x-shipping.shipping-work-end-history.list :shippingWorkEndHistories="$shipping_work_end_histories" />
    </div>
</x-app-layout>
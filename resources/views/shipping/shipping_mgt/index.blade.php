<x-app-layout>
    <x-shipping.shipping-mgt.shipping-group-select :shippingGroups="$shipping_groups" :shippingMethods="$shipping_methods" />
    <div class="flex flex-row my-3">
        <x-shipping.shipping-mgt.operation-div />
        <x-pagination :pages="$orders" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.shipping-mgt.search route="shipping_mgt.index" :bases="$bases" :orderCategories="$order_categories" :deliveryCompanies="$delivery_companies" :orderMarks="$order_marks" />
        <x-shipping.shipping-mgt.list :orders="$orders" />
    </div>
</x-app-layout>
@if(!is_null($shipping_group))
    <x-shipping.shipping-mgt.shipping-group-update-modal :shippingGroup="$shipping_group" />
@endif
<x-shipping.shipping-mgt.select-order-document-modal />
@vite(['resources/js/shipping/shipping_mgt/shipping_mgt.js'])
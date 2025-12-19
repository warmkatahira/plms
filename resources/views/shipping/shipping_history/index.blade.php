<x-app-layout>
    <div class="flex flex-row my-3">
        <x-shipping.shipping-history.operation-div />
        <x-pagination :pages="$orders" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.shipping-history.search route="shipping_history.index" :bases="$bases" :orderCategories="$order_categories" :deliveryCompanies="$delivery_companies" />
        <x-shipping.shipping-history.list :orders="$orders" />
    </div>
</x-app-layout>
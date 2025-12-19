<x-app-layout>
    <div class="flex flex-row my-3">
        <x-shipping.abc-analysis.operation-div />
        <x-shipping.abc-analysis.display-switch />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.abc-analysis.search route="abc_analysis.index" :orderCategories="$order_categories" />
        @if(session('disp_type') === 'list')
            <x-shipping.abc-analysis.list :items="$items" />
        @endif
        @if(session('disp_type') === 'chart')
            <x-shipping.abc-analysis.chart />
        @endif
    </div>
</x-app-layout>
@vite(['resources/js/shipping/abc_analysis/abc_analysis.js'])
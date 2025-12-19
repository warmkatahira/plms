<x-app-layout>
    <div class="flex flex-row my-3">
        <x-api.api-history.operation-div />
        <x-pagination :pages="$api_histories" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-api.api-history.search route="api_history.index" :malls="$malls" :apiActions="$api_actions" :apiStatuses="$api_statuses" />
        <x-api.api-history.list :apiHistories="$api_histories" />
    </div>
</x-app-layout>
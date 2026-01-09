@props([
    'label',
    'value',
    'formatType',
    'decimal' => 1,
])

<div class="col-span-3 bg-white rounded-xl shadow-lg p-4">
    <p class="text-base text-gray-600 text-center tracking-wide">
        {{ $label }}
    </p>
    <p class="mt-2 text-2xl font-bold text-gray-800 text-center">
        @if($formatType === 'days')
            {{ number_format($value, $decimal) }}
        @elseif($formatType === 'date')
            {{ CarbonImmutable::parse($value)->isoFormat('YYYY年MM月DD日') }}
        @endif
        @if($formatType === 'days')
            <span class="ml-1 text-sm font-normal text-gray-400">日</span>
        @endif
    </p>
</div>
@props([
    'label',
    'value',
    'formatType',
    'decimal' => 1,
])

<div class="col-span-12 md:col-span-3 bg-white rounded-xl shadow-lg p-4">
    <p class="text-xl md:text-base text-gray-600 text-center tracking-wide">{{ $label }}</p>
    <p class="mt-2 text-3xl md:text-2xl font-bold text-gray-800 text-center">
        @if($formatType === 'day')
            {{ number_format($value, $decimal) }}
        @elseif($formatType === 'date')
            {{ CarbonImmutable::parse($value)->isoFormat('YYYY年MM月DD日') }}
        @elseif($formatType === 'hour')
            {{ number_format($value, $decimal) }}
        @endif
        @if($formatType === 'day')
            <span class="text-base md:text-sm font-normal text-gray-400">日</span>
        @elseif($formatType === 'hour')
            <span class="text-base md:text-sm font-normal text-gray-400">時間</span>
        @endif
    </p>
</div>
@props([
    'label',
    'value',
])

<div class="flex flex-row items-center">
    <p class="w-1/3 py-2 bg-theme-main text-white pl-2 border border-theme-main">{{ $label }}</p>
    <p class="w-2/3 py-2 pl-2 border border-black">{{ $value }}</p>
</div>
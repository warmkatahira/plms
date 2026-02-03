@props([
    'type',
    'label',
    'name',
    'index' => null,
    'value' => null,
])

<div class="flex flex-row w-full">
    <p class="p-3 bg-theme-main text-center text-white border-y border-l border-black w-4/12">{{ $label }}</p>
    <input type="{{ $type }}" name="{{ $name }}[]" class="p-3 bg-white text-sm border border-gray-400 w-8/12" value="{{ old($name.'.'.$index, $value) }}" autocomplete="off">
</div>
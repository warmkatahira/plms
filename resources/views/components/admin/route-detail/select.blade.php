@props([
    'label',
    'name',
    'items',
    'index' => null,
    'optionValue' => null,
    'optionText' => null,
    'value' => null,
])

<div class="flex flex-row w-full">
    <p class="p-3 bg-theme-main text-center text-white border-y border-l border-black w-4/12">{{ $label }}</p>
    <select name="{{ $name }}[]" class="text-xs w-8/12 p-3">
        <option value=""></option>
        @foreach($items as $key => $item)
            <option value="{{ $item->$optionValue }}" @selected(old("{$name}.{$index}", $value) == $item->$optionValue)>{{ $item->$optionText }}</option>
        @endforeach
    </select>
</div>
@props([
    'label',
    'id',
    'name',
    'selectItems',
    'optionValue',
    'optionText',
])

<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs">
        <option value=""></option>
        @foreach($selectItems as $item)
            <option value="{{ $item->$optionValue }}" @selected((string)session($id) === (string)$item->$optionValue)>{{ $item->$optionText }}</option>
        @endforeach
    </select>
</div>
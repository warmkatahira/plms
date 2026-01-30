@props([
    'label',
    'id',
    'name',
    'label0',
    'label1',
])

<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs">
        <option value="" @selected(is_null(session($id)))></option>
        <option value="1" @selected((string)old($id) === '1')>{{ $label1 }}</option>
        <option value="0" @selected((string)old($id) === '0')>{{ $label0 }}</option>
    </select>
</div>
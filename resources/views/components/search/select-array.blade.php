@props([
    'label',
    'id',
    'name',
    'items',
])

<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs">
        <option value=""></option>
        @foreach($items as $key => $item)
            <option value="{{ $key }}" @selected((string)session($id) === (string)$key)>{{ $item }}</option>
        @endforeach
    </select>
</div>
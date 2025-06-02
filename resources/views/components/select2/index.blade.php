@props([
    'name' => '',
    'id' => '',
    'elementSelector' => '',
     'parentElementIfExist' => '',
])

<div>
    <select name="{{ $name }}" id="{{ $id }}" class="form-select form-select-solid {{ $elementSelector }}"
            data-dropdown-parent="{{ $parentElementIfExist }}">
        <option></option>
    </select>
</div>

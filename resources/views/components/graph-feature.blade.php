@php
    $label = $label ?? '-';
    $value = $value ?? '-';
@endphp
<div class="mb-4">
    <span class="p-1 px-2 border-top border-left border-bottom text-dark">{{ $label }}</span>
    <span class="p-1 px-2 bg-teal border-top border-right border-bottom text-white">{{ $value }}</span>
</div>
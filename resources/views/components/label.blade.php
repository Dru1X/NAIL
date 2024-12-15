@props([
    'colour' => 'gray',
])

@php
$colour = [
    'gray'   => 'bg-gray-50 text-gray-700 ring-gray-600/20',
    'green'  => 'bg-green-50 text-green-700 ring-green-600/20',
    'orange' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
    'blue'   => 'bg-blue-50 text-blue-700 ring-blue-600/20',
][$colour];
@endphp

<span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{$colour}}">
    {{$slot}}
</span>

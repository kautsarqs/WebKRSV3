@props([
    'type' => 'text', // text, circle, rect
    'width' => 'w-full',
    'height' => 'h-4',
    'class' => ''
])

@php
    $baseClass = "skeleton animate-skeleton " . $class;
    
    if ($type === 'circle') {
        $baseClass .= " rounded-full";
    } elseif ($type === 'rect') {
        $baseClass .= " rounded-2xl";
    }
@endphp

<div class="{{ $baseClass }} {{ $width }} {{ $height }}"></div>

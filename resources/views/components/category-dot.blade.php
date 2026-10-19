@props(['id' => 0])

@php
    $palette = ['bg-madblue-500', 'bg-purple-500', 'bg-madorange-500', 'bg-teal-500', 'bg-emerald-500', 'bg-pink-500'];
    $color = $palette[((int) $id) % count($palette)];
@endphp

<span {{ $attributes->merge(['class' => "inline-block h-2.5 w-2.5 flex-none rounded-full $color"]) }}></span>

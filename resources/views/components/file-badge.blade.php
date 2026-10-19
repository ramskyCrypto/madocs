@props(['ext' => '', 'size' => 'h-9 w-9'])

@php
    $e = strtolower((string) $ext);
    $map = [
        'pdf'  => ['PDF', 'bg-red-500'],
        'doc'  => ['DOC', 'bg-madblue-500'],
        'docx' => ['DOC', 'bg-madblue-500'],
        'xls'  => ['XLS', 'bg-emerald-600'],
        'xlsx' => ['XLS', 'bg-emerald-600'],
        'ppt'  => ['PPT', 'bg-madorange-500'],
        'pptx' => ['PPT', 'bg-madorange-500'],
    ];
    [$label, $color] = $map[$e] ?? [strtoupper(substr($e, 0, 3)) ?: 'FILE', 'bg-slate-500'];
@endphp

<span {{ $attributes->merge(['class' => "flex flex-none items-center justify-center rounded-lg text-[10px] font-extrabold tracking-wide text-white $size $color"]) }}>{{ $label }}</span>

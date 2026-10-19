<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') — Madocs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        {{-- Dekorasi geometris aksen oranye --}}
        <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rotate-12 rounded-3xl bg-madorange-100/60"></div>
        <div class="pointer-events-none absolute -right-10 top-1/3 h-32 w-32 rotate-45 rounded-2xl border-4 border-madorange-100"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/4 h-24 w-24 rounded-full bg-madblue-50"></div>

        @yield('content')
    </div>
</body>
</html>

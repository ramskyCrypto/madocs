<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kedaluwarsa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-100">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4">
        <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rotate-12 rounded-3xl bg-madorange-100/60"></div>
        <div class="pointer-events-none absolute -right-10 bottom-10 h-32 w-32 rotate-45 rounded-2xl border-4 border-madorange-100"></div>

        <div class="relative z-10 w-full max-w-md text-center">
            <x-logo class="mx-auto mb-6 h-14 w-14" />
            <p class="text-6xl font-extrabold text-madblue-500">419</p>
            <h1 class="mt-2 text-xl font-bold text-slate-800">Sesi Kedaluwarsa</h1>
            <p class="mt-2 text-sm text-slate-500">Sesi Anda telah berakhir. Silakan muat ulang halaman dan coba lagi.</p>
            <a href="/" class="madocs-btn-primary mt-6 inline-flex">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>

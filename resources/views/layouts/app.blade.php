<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Madocs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- Overlay mobile --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"
            style="display: none;"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 w-72 transform bg-cream-100 border-r border-cream-200 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-full flex-col">
                <div class="flex items-center gap-3 px-5 py-5">
                    <x-logo class="h-10 w-10" />
                    <div>
                        <p class="text-lg font-extrabold text-madblue-600 leading-none">Madocs</p>
                        <p class="text-[11px] text-slate-400 leading-tight">Management Documents School</p>
                    </div>
                    <button @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-slate-600 lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 pb-5 space-y-6">
                    @can('create', \App\Models\Document::class)
                    <a href="{{ route('documents.create') }}" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:shadow-md">
                        <span class="text-xl font-bold leading-none text-madblue-500">+</span>
                        Upload Dokumen
                    </a>
                    @endcan

                    <div>
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 12l8.25-8.25L20.25 12M4.5 9.75V20.25a.75.75 0 00.75.75H9a.75.75 0 00.75-.75V15a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v5.25a.75.75 0 00.75.75h3.75a.75.75 0 00.75-.75V9.75" /></svg>
                            Dashboard
                        </a>
                    </div>

                    <div>
                        <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Dokumen</p>
                        <div class="space-y-1">
                            <a href="{{ route('documents.index') }}" class="nav-link {{ (request()->routeIs('documents.index') && ! request()->filled('category_id')) || request()->routeIs('documents.show') ? 'active' : '' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Semua Dokumen
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5A1.5 1.5 0 014.5 6h4.379a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H19.5A1.5 1.5 0 0121 9.5v8A1.5 1.5 0 0119.5 19h-15A1.5 1.5 0 013 17.5v-10z" /></svg>
                                Kelola Kategori
                            </a>
                        </div>
                    </div>

                    @if (isset($sidebarCategories) && $sidebarCategories->isNotEmpty())
                    <div>
                        <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</p>
                        <div class="space-y-0.5">
                            @foreach ($sidebarCategories as $c)
                                <a href="{{ route('documents.index', ['category_id' => $c->id]) }}"
                                   class="nav-link !py-1.5 {{ request()->routeIs('documents.index') && request('category_id') == $c->id ? 'active' : '' }}">
                                    <x-category-dot :id="$c->id" class="mx-1.5" />
                                    <span class="truncate">{{ $c->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if (auth()->user()->isAdmin())
                    <div>
                        <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Manajemen</p>
                        <div class="space-y-1">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0112.75 0zM15.75 8.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                                Pengguna
                            </a>
                            <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
                                Riwayat Aktivitas
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Sistem</p>
                        <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Pengaturan
                        </a>
                    </div>
                    @endif
                </nav>

                <div class="border-t border-cream-200 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-full text-madorange-600 hover:bg-madorange-50 hover:text-madorange-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15m-3 -3h12m0 0l-3-3m3 3l-3 3" /></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex min-h-screen flex-1 flex-col lg:pl-0">
            {{-- Header --}}
            <header class="sticky top-0 z-20 flex items-center gap-4 bg-cream-50/90 px-4 py-3 backdrop-blur lg:px-8">
                <button @click="sidebarOpen = true" class="text-slate-500 hover:text-madblue-600 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                </button>

                <form action="{{ route('documents.index') }}" method="GET" class="hidden max-w-xl flex-1 md:block">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-3 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                        <input type="search" name="q" placeholder="Cari dokumen, nomor, atau nama pengunggah" class="block w-full rounded-full border-transparent bg-cream-100 py-2.5 pl-10 pr-4 text-sm placeholder:text-slate-400 focus:border-madblue-400 focus:bg-white focus:ring-madblue-400" value="{{ request('q') }}">
                    </div>
                </form>

                <div class="ml-auto flex items-center gap-3" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-full px-2 py-1.5 hover:bg-cream-100">
                        <span class="hidden text-right sm:block">
                            <span class="block text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                            <span class="block text-xs text-madblue-600">{{ auth()->user()->roleLabel() }}</span>
                        </span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-madblue-50 text-sm font-bold text-madblue-600">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </button>
                    <div x-show="open" x-transition style="display:none" class="absolute right-4 top-14 z-50 w-48 rounded-xl border border-slate-100 bg-white p-2 shadow-lg">
                        <div class="px-3 py-2 text-xs text-slate-400">Masuk sebagai</div>
                        <div class="px-3 pb-2 text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm text-madorange-600 hover:bg-madorange-50">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 pb-8 pt-2 lg:px-8">
                <x-flash />
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Selamat datang, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-slate-500">Ringkasan dokumen sekolah hari ini.</p>
    </div>

    {{-- Ringkasan angka --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 {{ $stats['total_pengguna'] !== null ? 'xl:grid-cols-6' : 'xl:grid-cols-5' }}">
        <div class="stat-card">
            <p class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_dokumen'] }}</p>
            <p class="text-xs text-slate-500">Total dokumen</p>
        </div>
        <div class="stat-card stat-card-warn">
            <p class="text-2xl font-extrabold tracking-tight text-madorange-500">{{ $stats['dokumen_baru'] }}</p>
            <p class="text-xs text-slate-500">Baru (7 hari terakhir)</p>
        </div>
        <div class="stat-card">
            <p class="text-2xl font-extrabold tracking-tight text-emerald-600">{{ $stats['dokumen_aktif'] }}</p>
            <p class="text-xs text-slate-500">Dokumen aktif</p>
        </div>
        <div class="stat-card">
            <p class="text-2xl font-extrabold tracking-tight text-slate-500">{{ $stats['dokumen_arsip'] }}</p>
            <p class="text-xs text-slate-500">Dokumen arsip</p>
        </div>
        <div class="stat-card">
            <p class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_kategori'] }}</p>
            <p class="text-xs text-slate-500">Kategori</p>
        </div>
        @if ($stats['total_pengguna'] !== null)
        <div class="stat-card">
            <p class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_pengguna'] }}</p>
            <p class="text-xs text-slate-500">Pengguna</p>
        </div>
        @endif
    </div>

    {{-- Kategori sebagai "folder" --}}
    @if ($dokumenPerKategori->isNotEmpty())
    <section>
        <h2 class="mb-3 text-sm font-bold text-slate-700">Kategori</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($dokumenPerKategori->take(8) as $cat)
                <a href="{{ route('documents.index', ['category_id' => $cat->id]) }}" class="folder-chip">
                    <x-category-dot :id="$cat->id" />
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-700">{{ $cat->name }}</p>
                        <p class="text-xs text-slate-400">{{ $cat->documents_count }} dokumen</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Dokumen terbaru --}}
    <section>
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-700">Dokumen Terbaru</h2>
            <a href="{{ route('documents.index') }}" class="text-xs font-semibold text-madblue-600 hover:underline">Lihat semua</a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200/70 bg-white">
            @forelse ($dokumenTerbaru as $doc)
                <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3 last:border-0 hover:bg-cream-50">
                    <x-file-badge :ext="$doc->file_extension" />
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('documents.show', $doc) }}" class="block truncate text-sm font-semibold text-slate-700 hover:text-madblue-600">{{ $doc->name }}</a>
                        <p class="truncate text-xs text-slate-400">{{ $doc->category->name ?? '-' }} • {{ $doc->user->name ?? '-' }} • {{ $doc->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="{{ $doc->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($doc->status) }}</span>
                    <a href="{{ route('documents.show', $doc) }}" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Detail</a>
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-slate-400">Belum ada dokumen.</p>
            @endforelse
        </div>
    </section>

    {{-- Grafik --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="madocs-card">
            <h2 class="mb-4 text-sm font-bold text-slate-700">Dokumen per Kategori</h2>
            @if ($dokumenPerKategori->sum('documents_count') === 0)
                <p class="text-sm text-slate-400">Belum ada data dokumen.</p>
            @else
                <div class="space-y-3">
                    @php $max = max(1, $dokumenPerKategori->max('documents_count')); @endphp
                    @foreach ($dokumenPerKategori->take(8) as $cat)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs text-slate-500">
                                <span>{{ $cat->name }}</span>
                                <span class="font-semibold text-slate-700">{{ $cat->documents_count }}</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-cream-100">
                                <div class="h-2 rounded-full bg-madblue-500" style="width: {{ $cat->documents_count / $max * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="madocs-card">
            <h2 class="mb-4 text-sm font-bold text-slate-700">Dokumen Masuk per Bulan (6 Bulan Terakhir)</h2>
            @if ($dokumenPerBulan->isEmpty())
                <p class="text-sm text-slate-400">Belum ada data dokumen pada rentang ini.</p>
            @else
                @php $maxBulan = max(1, $dokumenPerBulan->max('total')); @endphp
                <div class="flex h-40 items-end gap-3">
                    @foreach ($dokumenPerBulan as $row)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="w-full rounded-t-lg bg-madorange-400" style="height: {{ max(6, $row->total / $maxBulan * 130) }}px"></div>
                            <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::createFromFormat('Y-m', $row->bulan)->translatedFormat('M Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if (auth()->user()->isAdmin())
    {{-- Aktivitas terbaru --}}
    <section>
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-700">Aktivitas Terbaru</h2>
            <a href="{{ route('activities.index') }}" class="text-xs font-semibold text-madblue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200/70 bg-white">
            @forelse ($aktivitasTerbaru as $activity)
                <div class="flex items-start gap-3 border-b border-slate-100 px-4 py-3 last:border-0">
                    <span class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-madorange-500"></span>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-700">
                            <span class="font-semibold">{{ $activity->user->name ?? 'Pengguna' }}</span>
                            {{ $activity->description }}
                        </p>
                        <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-slate-400">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </section>
    @endif
</div>
@endsection

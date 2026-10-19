@extends('layouts.app')

@section('title', 'Semua Dokumen')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Semua Dokumen</h1>
            <p class="text-sm text-slate-500">Kelola, cari, dan unduh dokumen sekolah.</p>
        </div>
        @can('create', \App\Models\Document::class)
            <a href="{{ route('documents.create') }}" class="madocs-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Upload Dokumen
            </a>
        @endcan
    </div>

    {{-- Search & Filter --}}
    <div class="madocs-card">
        <form method="GET" action="{{ route('documents.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div class="lg:col-span-2">
                <label class="madocs-label">Cari Dokumen</label>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, nomor, deskripsi..." class="madocs-input py-2">
            </div>
            <div>
                <label class="madocs-label">Kategori</label>
                <select name="category_id" class="madocs-input py-2">
                    <option value="">Semua</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(($filters['category_id'] ?? null) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="madocs-label">Status</label>
                <select name="status" class="madocs-input py-2">
                    <option value="">Semua</option>
                    <option value="aktif" @selected(($filters['status'] ?? null) === 'aktif')>Aktif</option>
                    <option value="arsip" @selected(($filters['status'] ?? null) === 'arsip')>Arsip</option>
                </select>
            </div>
            <div>
                <label class="madocs-label">Format</label>
                <select name="format" class="madocs-input py-2">
                    <option value="">Semua</option>
                    @foreach (['pdf','doc','docx','xls','xlsx','ppt','pptx'] as $ext)
                        <option value="{{ $ext }}" @selected(($filters['format'] ?? null) === $ext)>{{ strtoupper($ext) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="madocs-label">Uploader</label>
                <select name="uploader_id" class="madocs-input py-2">
                    <option value="">Semua</option>
                    @foreach ($uploaders as $u)
                        <option value="{{ $u->id }}" @selected(($filters['uploader_id'] ?? null) == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 lg:col-span-6">
                <button type="submit" class="madocs-btn-primary">Terapkan Filter</button>
                <a href="{{ route('documents.index') }}" class="madocs-btn-secondary">Reset Filter</a>
            </div>
        </form>
    </div>

    {{-- Desktop table --}}
    <div class="hidden overflow-x-auto rounded-xl border border-slate-200/70 bg-white lg:block">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="doc-th">Nama</th>
                    <th class="doc-th">Kategori</th>
                    <th class="doc-th">Uploader</th>
                    <th class="doc-th">Tanggal</th>
                    <th class="doc-th">Ukuran</th>
                    <th class="doc-th">Status</th>
                    <th class="doc-th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $doc)
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-cream-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <x-file-badge :ext="$doc->file_extension" />
                                <div class="min-w-0">
                                    <a href="{{ route('documents.show', $doc) }}" class="block max-w-xs truncate font-semibold text-slate-700 hover:text-madblue-600">{{ $doc->name }}</a>
                                    <p class="text-xs text-slate-400">{{ $doc->document_number ?: 'Tanpa nomor' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="doc-td">
                            <span class="inline-flex items-center gap-2">
                                @if ($doc->category)<x-category-dot :id="$doc->category_id" />@endif
                                {{ $doc->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="doc-td">{{ $doc->user->name ?? '-' }}</td>
                        <td class="doc-td">{{ $doc->document_date->translatedFormat('d M Y') }}</td>
                        <td class="doc-td">{{ $doc->humanFileSize() }}</td>
                        <td class="px-4 py-3"><span class="{{ $doc->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($doc->status) }}</span></td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('documents.show', $doc) }}" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Detail</a>
                                <a href="{{ route('documents.download', $doc) }}" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Download</a>
                                @can('update', $doc)
                                    <a href="{{ route('documents.edit', $doc) }}" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Edit</a>
                                @endcan
                                @can('delete', $doc)
                                    <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="madocs-btn-danger !px-3 !py-1.5 text-xs">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-3 lg:hidden">
        @foreach ($documents as $doc)
            <div class="madocs-card">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex min-w-0 items-center gap-3">
                        <x-file-badge :ext="$doc->file_extension" />
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-700">{{ $doc->name }}</p>
                            <p class="text-xs text-slate-400">{{ $doc->document_number ?: '-' }}</p>
                        </div>
                    </div>
                    <span class="{{ $doc->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($doc->status) }}</span>
                </div>
                <dl class="mt-3 grid grid-cols-2 gap-2 text-xs text-slate-500">
                    <div><dt class="text-slate-400">Kategori</dt><dd>{{ $doc->category->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400">Tanggal</dt><dd>{{ $doc->document_date->translatedFormat('d M Y') }}</dd></div>
                    <div><dt class="text-slate-400">Uploader</dt><dd>{{ $doc->user->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400">Format</dt><dd class="uppercase">{{ $doc->file_extension }} • {{ $doc->humanFileSize() }}</dd></div>
                </dl>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('documents.show', $doc) }}" class="madocs-btn-secondary flex-1 !px-3 !py-1.5 text-xs">Detail</a>
                    <a href="{{ route('documents.download', $doc) }}" class="madocs-btn-secondary flex-1 !px-3 !py-1.5 text-xs">Download</a>
                    @can('update', $doc)
                        <a href="{{ route('documents.edit', $doc) }}" class="madocs-btn-secondary flex-1 !px-3 !py-1.5 text-xs">Edit</a>
                    @endcan
                    @can('delete', $doc)
                        <form method="POST" action="{{ route('documents.destroy', $doc) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="madocs-btn-danger w-full !px-3 !py-1.5 text-xs">Hapus</button>
                        </form>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>

    @if ($documents->isEmpty())
        <div class="madocs-card flex flex-col items-center py-12 text-center">
            <svg class="h-12 w-12 text-madblue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            <p class="mt-3 font-semibold text-slate-600">Belum ada dokumen</p>
            <p class="text-sm text-slate-400">Silakan upload dokumen pertama Anda.</p>
            @can('create', \App\Models\Document::class)
                <a href="{{ route('documents.create') }}" class="madocs-btn-primary mt-4">Upload Dokumen</a>
            @endcan
        </div>
    @else
        <div>{{ $documents->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $document->name }}</h1>
            <p class="text-sm text-slate-500">{{ $document->document_number ?: 'Tanpa nomor dokumen' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('documents.download', $document) }}" class="madocs-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download
            </a>
            @can('update', $document)
                <a href="{{ route('documents.edit', $document) }}" class="madocs-btn-secondary">Edit</a>
            @endcan
            @can('delete', $document)
                <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="madocs-btn-danger">Hapus</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="madocs-card lg:col-span-2">
            <h2 class="mb-4 text-sm font-bold text-slate-700">Informasi Dokumen</h2>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs text-slate-400">Kategori</dt>
                    <dd class="mt-1"><span class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">@if ($document->category)<x-category-dot :id="$document->category_id" />@endif{{ $document->category->name ?? '-' }}</span></dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Status</dt>
                    <dd class="mt-1"><span class="{{ $document->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($document->status) }}</span></dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Tanggal Dokumen</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $document->document_date->translatedFormat('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Visibilitas</dt>
                    <dd class="mt-1"><span class="badge-orange">{{ \App\Models\Document::VISIBILITIES[$document->visibility] ?? $document->visibility }}</span></dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Diunggah Oleh</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $document->user->name ?? 'Pengguna tidak diketahui' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Tanggal Upload</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $document->created_at->translatedFormat('d F Y, H:i') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-400">Deskripsi</dt>
                    <dd class="mt-1 text-sm text-slate-600">{{ $document->description ?: '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="madocs-card">
            <h2 class="mb-4 text-sm font-bold text-slate-700">Berkas</h2>
            <div class="flex items-center gap-3 rounded-lg bg-cream-100 p-3">
                <x-file-badge :ext="$document->file_extension" size="h-10 w-10" />
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-slate-700">{{ $document->file_name }}</p>
                    <p class="text-xs text-slate-400">{{ $document->humanFileSize() }}</p>
                </div>
            </div>

            @if ($document->isPdf())
                <div class="mt-4 overflow-hidden rounded-lg border border-slate-100">
                    <iframe src="{{ route('documents.preview', $document) }}" class="h-72 w-full" title="Pratinjau PDF"></iframe>
                </div>
            @else
                <p class="mt-4 text-xs text-slate-400">Pratinjau langsung hanya tersedia untuk file PDF. Silakan download untuk membuka file ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('content')
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-extrabold text-slate-800">Riwayat Aktivitas</h1>
        <p class="text-sm text-slate-500">Jejak audit seluruh aksi penting yang terjadi di Madocs.</p>
    </div>

    <div class="madocs-card">
        <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div>
                <label class="madocs-label">Jenis Aksi</label>
                <select name="action" class="madocs-input py-2">
                    <option value="">Semua</option>
                    @foreach (['login' => 'Login', 'logout' => 'Logout', 'upload_document' => 'Upload Dokumen', 'edit_document' => 'Edit Dokumen', 'delete_document' => 'Hapus Dokumen', 'download_document' => 'Download Dokumen', 'create_category' => 'Tambah Kategori', 'edit_category' => 'Edit Kategori', 'delete_category' => 'Hapus Kategori', 'create_user' => 'Tambah Pengguna', 'edit_user' => 'Edit Pengguna', 'delete_user' => 'Hapus Pengguna', 'edit_setting' => 'Ubah Pengaturan'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['action'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 sm:col-span-2">
                <button type="submit" class="madocs-btn-primary">Terapkan Filter</button>
                <a href="{{ route('activities.index') }}" class="madocs-btn-secondary">Reset Filter</a>
            </div>
        </form>
    </div>

    <div class="madocs-card overflow-hidden !p-0">
        @if ($activities->isEmpty())
            <div class="flex flex-col items-center py-12 text-center">
                <svg class="h-12 w-12 text-madblue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
                <p class="mt-3 font-semibold text-slate-600">Belum ada aktivitas</p>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($activities as $activity)
                    <li class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-sm text-slate-700">
                                <span class="font-semibold">{{ $activity->user?->name ?? 'Pengguna terhapus' }}</span>
                                <span class="badge-blue ml-1">{{ $activity->user?->roleLabel() ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-slate-500">{{ $activity->description }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right text-xs text-slate-400">
                            <p>{{ $activity->created_at->translatedFormat('d M Y H:i') }}</p>
                            <p>IP: {{ $activity->ip_address ?? '-' }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @if ($activities->isNotEmpty())
        <div>{{ $activities->links() }}</div>
    @endif
</div>
@endsection

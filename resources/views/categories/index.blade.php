@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="space-y-5" x-data="{ showCreate: false, editing: null }">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">Kategori Dokumen</h1>
            <p class="text-sm text-slate-500">Kelola kategori untuk mengorganisir dokumen sekolah.</p>
        </div>
        @can('create', \App\Models\Category::class)
            <button @click="showCreate = true" class="madocs-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Kategori
            </button>
        @endcan
    </div>

    @if ($categories->isEmpty())
        <div class="madocs-card flex flex-col items-center py-12 text-center">
            <p class="font-semibold text-slate-600">Belum ada kategori</p>
            <p class="text-sm text-slate-400">Tambahkan kategori pertama untuk mulai mengorganisir dokumen.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <div class="madocs-card">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-bold text-slate-700">{{ $category->name }}</p>
                            <p class="mt-1 text-xs text-slate-400 line-clamp-2">{{ $category->description ?: 'Tidak ada deskripsi.' }}</p>
                        </div>
                        <span class="badge-blue whitespace-nowrap">{{ $category->documents_count }} dok.</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Dibuat {{ $category->created_at->translatedFormat('d M Y') }}</p>

                    @canany(['update', 'delete'], $category)
                        <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">
                            @can('update', $category)
                                <button
                                    @click='editing = {{ $category->only(["id","name","description"]) }}'
                                    class="madocs-btn-secondary flex-1 !px-3 !py-1.5 text-xs">Edit</button>
                            @endcan
                            @can('delete', $category)
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="madocs-btn-danger w-full !px-3 !py-1.5 text-xs">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    @endcanany
                </div>
            @endforeach
        </div>

        <div>{{ $categories->links() }}</div>
    @endif

    {{-- Modal: Tambah Kategori --}}
    <div x-show="showCreate" style="display:none" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4">
        <div @click.outside="showCreate = false" class="w-full max-w-md rounded-xl2 bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <h3 class="mb-4 text-lg font-bold text-slate-800">Tambah Kategori</h3>
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="madocs-label">Nama Kategori <span class="text-madorange-500">*</span></label>
                    <input type="text" name="name" class="madocs-input py-2.5" required>
                </div>
                <div>
                    <label class="madocs-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="madocs-input py-2.5"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showCreate = false" class="madocs-btn-secondary">Batal</button>
                    <button type="submit" class="madocs-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Kategori --}}
    <div x-show="editing" style="display:none" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4">
        <div @click.outside="editing = null" class="w-full max-w-md rounded-xl2 bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto" x-show="editing" x-cloak>
            <h3 class="mb-4 text-lg font-bold text-slate-800">Edit Kategori</h3>
            <template x-if="editing">
                <form method="POST" :action="'/categories/' + editing.id" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="madocs-label">Nama Kategori <span class="text-madorange-500">*</span></label>
                        <input type="text" name="name" :value="editing.name" class="madocs-input py-2.5" required>
                    </div>
                    <div>
                        <label class="madocs-label">Deskripsi</label>
                        <textarea name="description" rows="3" class="madocs-input py-2.5" x-text="editing.description"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="editing = null" class="madocs-btn-secondary">Batal</button>
                        <button type="submit" class="madocs-btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div>
        <h1 class="text-xl font-extrabold text-slate-800">Edit Dokumen</h1>
        <p class="text-sm text-slate-500">Perbarui informasi dokumen "{{ $document->name }}".</p>
    </div>

    <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data" class="madocs-card space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Nama Dokumen <span class="text-madorange-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $document->name) }}" class="madocs-input py-2.5 @error('name') border-madorange-400 @enderror" required>
                @error('name')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Nomor Dokumen</label>
                <input type="text" name="document_number" value="{{ old('document_number', $document->document_number) }}" class="madocs-input py-2.5">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Kategori <span class="text-madorange-500">*</span></label>
                <select name="category_id" class="madocs-input py-2.5" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $document->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="madocs-label">Tanggal Dokumen <span class="text-madorange-500">*</span></label>
                <input type="date" name="document_date" value="{{ old('document_date', $document->document_date->format('Y-m-d')) }}" class="madocs-input py-2.5" required>
            </div>
        </div>

        <div>
            <label class="madocs-label">Deskripsi</label>
            <textarea name="description" rows="3" class="madocs-input py-2.5">{{ old('description', $document->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Status <span class="text-madorange-500">*</span></label>
                <select name="status" class="madocs-input py-2.5" required>
                    <option value="aktif" @selected(old('status', $document->status) === 'aktif')>Aktif</option>
                    <option value="arsip" @selected(old('status', $document->status) === 'arsip')>Arsip</option>
                </select>
            </div>
            <div>
                <label class="madocs-label">Visibilitas <span class="text-madorange-500">*</span></label>
                <select name="visibility" class="madocs-input py-2.5" required>
                    @foreach (\App\Models\Document::VISIBILITIES as $key => $label)
                        <option value="{{ $key }}" @selected(old('visibility', $document->visibility) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="madocs-label">Ganti File (opsional)</label>
            <div class="mb-2 rounded-lg bg-cream-100 px-3 py-2 text-xs text-slate-500">
                File saat ini: <span class="font-semibold">{{ $document->file_name }}</span> ({{ $document->humanFileSize() }})
            </div>
            <input type="file" name="file" class="madocs-input py-2 @error('file') border-madorange-400 @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            <p class="mt-1 text-xs text-slate-400">Kosongkan jika tidak ingin mengganti file. Maksimal 10 MB.</p>
            @error('file')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('documents.show', $document) }}" class="madocs-btn-secondary">Batal</a>
            <button type="submit" class="madocs-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div>
        <h1 class="text-xl font-extrabold text-slate-800">Upload Dokumen</h1>
        <p class="text-sm text-slate-500">Lengkapi informasi dokumen dan unggah berkasnya.</p>
    </div>

    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="madocs-card space-y-5">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Nama Dokumen <span class="text-madorange-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="madocs-input py-2.5 @error('name') border-madorange-400 @enderror" required>
                @error('name')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Nomor Dokumen</label>
                <input type="text" name="document_number" value="{{ old('document_number') }}" class="madocs-input py-2.5">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Kategori <span class="text-madorange-500">*</span></label>
                <select name="category_id" class="madocs-input py-2.5 @error('category_id') border-madorange-400 @enderror" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Tanggal Dokumen <span class="text-madorange-500">*</span></label>
                <input type="date" name="document_date" value="{{ old('document_date') }}" class="madocs-input py-2.5 @error('document_date') border-madorange-400 @enderror" required>
                @error('document_date')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="madocs-label">Deskripsi</label>
            <textarea name="description" rows="3" class="madocs-input py-2.5">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Status <span class="text-madorange-500">*</span></label>
                <select name="status" class="madocs-input py-2.5" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="arsip" @selected(old('status') === 'arsip')>Arsip</option>
                </select>
            </div>
            <div>
                <label class="madocs-label">Visibilitas <span class="text-madorange-500">*</span></label>
                <select name="visibility" class="madocs-input py-2.5" required>
                    @foreach (\App\Models\Document::VISIBILITIES as $key => $label)
                        <option value="{{ $key }}" @selected(old('visibility', 'internal') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="madocs-label">File Dokumen <span class="text-madorange-500">*</span></label>
            <input type="file" name="file" class="madocs-input py-2 @error('file') border-madorange-400 @enderror" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            <p class="mt-1 text-xs text-slate-400">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10 MB.</p>
            @error('file')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('documents.index') }}" class="madocs-btn-secondary">Batal</a>
            <button type="submit" class="madocs-btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

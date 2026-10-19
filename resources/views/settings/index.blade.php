@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <div>
        <h1 class="text-xl font-extrabold text-slate-800">Pengaturan Sistem</h1>
        <p class="text-sm text-slate-500">Konfigurasi identitas aplikasi dan kebijakan upload dokumen.</p>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="madocs-card space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Nama Aplikasi</label>
                <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" class="madocs-input @error('app_name') border-madorange-400 @enderror">
                @error('app_name')<p class="mt-1 text-xs text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Nama Sekolah</label>
                <input type="text" name="school_name" value="{{ old('school_name', $settings['school_name']) }}" class="madocs-input @error('school_name') border-madorange-400 @enderror">
                @error('school_name')<p class="mt-1 text-xs text-madorange-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="madocs-label">Informasi Sekolah</label>
            <textarea name="school_info" rows="3" class="madocs-input">{{ old('school_info', $settings['school_info']) }}</textarea>
        </div>

        <div>
            <label class="madocs-label">Logo Sekolah</label>
            @if ($settings['logo_path'])
                <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo_path']) }}" alt="Logo sekolah" class="mb-2 h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200">
            @endif
            <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-madblue-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-madblue-600 hover:file:bg-madblue-100">
            @error('logo')<p class="mt-1 text-xs text-madorange-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Maksimal Ukuran Upload (MB)</label>
                <input type="number" min="1" max="50" name="max_upload_mb" value="{{ old('max_upload_mb', $settings['max_upload_mb']) }}" class="madocs-input @error('max_upload_mb') border-madorange-400 @enderror">
                @error('max_upload_mb')<p class="mt-1 text-xs text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Format File Diperbolehkan</label>
                <input type="text" name="allowed_ext" value="{{ old('allowed_ext', $settings['allowed_ext']) }}" class="madocs-input @error('allowed_ext') border-madorange-400 @enderror" placeholder="pdf,doc,docx,xls,xlsx,ppt,pptx">
                <p class="mt-1 text-xs text-slate-400">Pisahkan dengan koma, tanpa titik.</p>
                @error('allowed_ext')<p class="mt-1 text-xs text-madorange-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
            <button type="submit" class="madocs-btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection

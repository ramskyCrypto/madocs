@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mx-auto max-w-2xl space-y-5">
    <h1 class="text-xl font-extrabold text-slate-800">Tambah Pengguna</h1>

    <form method="POST" action="{{ route('users.store') }}" class="madocs-card space-y-4">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Nama Lengkap <span class="text-madorange-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="madocs-input py-2.5 @error('name') border-madorange-400 @enderror" required>
                @error('name')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Username <span class="text-madorange-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" class="madocs-input py-2.5 @error('username') border-madorange-400 @enderror" required>
                @error('username')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="madocs-label">Email <span class="text-madorange-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="madocs-input py-2.5 @error('email') border-madorange-400 @enderror" required>
            @error('email')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Role <span class="text-madorange-500">*</span></label>
                <select name="role" class="madocs-input py-2.5" required>
                    @foreach (\App\Models\User::ROLES as $key => $label)
                        <option value="{{ $key }}" @selected(old('role') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="madocs-label">Status <span class="text-madorange-500">*</span></label>
                <select name="status" class="madocs-input py-2.5" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Kata Sandi <span class="text-madorange-500">*</span></label>
                <input type="password" name="password" class="madocs-input py-2.5 @error('password') border-madorange-400 @enderror" required>
                @error('password')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Konfirmasi Kata Sandi <span class="text-madorange-500">*</span></label>
                <input type="password" name="password_confirmation" class="madocs-input py-2.5" required>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('users.index') }}" class="madocs-btn-secondary">Batal</a>
            <button type="submit" class="madocs-btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

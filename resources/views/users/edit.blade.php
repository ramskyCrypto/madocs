@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mx-auto max-w-2xl space-y-5">
    <h1 class="text-xl font-extrabold text-slate-800">Edit Pengguna</h1>

    <form method="POST" action="{{ route('users.update', $user) }}" class="madocs-card space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Nama Lengkap <span class="text-madorange-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="madocs-input py-2.5" required>
            </div>
            <div>
                <label class="madocs-label">Username <span class="text-madorange-500">*</span></label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="madocs-input py-2.5" required>
            </div>
        </div>

        <div>
            <label class="madocs-label">Email <span class="text-madorange-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="madocs-input py-2.5" required>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Role <span class="text-madorange-500">*</span></label>
                <select name="role" class="madocs-input py-2.5" required>
                    @foreach (\App\Models\User::ROLES as $key => $label)
                        <option value="{{ $key }}" @selected(old('role', $user->role) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="madocs-label">Status <span class="text-madorange-500">*</span></label>
                <select name="status" class="madocs-input py-2.5" required @if($user->id === auth()->id()) disabled @endif>
                    <option value="aktif" @selected(old('status', $user->status) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $user->status) === 'nonaktif')>Nonaktif</option>
                </select>
                @if ($user->id === auth()->id())
                    <input type="hidden" name="status" value="{{ $user->status }}">
                    <p class="mt-1 text-xs text-slate-400">Anda tidak dapat mengubah status akun Anda sendiri.</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="madocs-label">Kata Sandi Baru</label>
                <input type="password" name="password" class="madocs-input py-2.5" placeholder="Kosongkan jika tidak diubah">
                @error('password')<p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="madocs-label">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="madocs-input py-2.5">
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('users.index') }}" class="madocs-btn-secondary">Batal</a>
            <button type="submit" class="madocs-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

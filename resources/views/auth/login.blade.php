@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="relative z-10 w-full max-w-md">
    <div class="madocs-card !p-8">
        <div class="mb-6 flex flex-col items-center text-center">
            <x-logo class="h-14 w-14" />
            <h1 class="mt-4 text-2xl font-extrabold text-madblue-600">Madocs</h1>
            <p class="text-sm text-slate-500">Management Documents School</p>
            <p class="mt-3 text-sm text-slate-500">Masuk untuk mengelola dokumen sekolah Anda dengan aman dan rapi.</p>
        </div>

        <x-flash />

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <div>
                <label for="username" class="madocs-label">Username atau Email</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="madocs-input py-2.5 @error('username') border-madorange-400 @enderror"
                    placeholder="Masukkan username atau email">
                @error('username')
                    <p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="madocs-label">Kata Sandi</label>
                <input id="password" type="password" name="password" required
                    class="madocs-input py-2.5 @error('password') border-madorange-400 @enderror"
                    placeholder="Masukkan kata sandi">
                @error('password')
                    <p class="mt-1 text-sm text-madorange-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-madblue-500 focus:ring-madblue-400">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="madocs-btn-primary w-full py-3" :disabled="loading">
                <span x-show="!loading">Masuk</span>
                <span x-show="loading" style="display:none" class="flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    Memproses...
                </span>
            </button>
        </form>

        <div class="mt-6 rounded-xl bg-cream-100 p-3 text-center text-xs text-slate-500">
            Lupa kata sandi? Hubungi administrator sekolah untuk melakukan reset akun Anda.
        </div>
    </div>
</div>
@endsection

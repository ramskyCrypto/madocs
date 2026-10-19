@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola akun admin, kepala sekolah, guru, staff, dan siswa.</p>
        </div>
        <a href="{{ route('users.create') }}" class="madocs-btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Pengguna
        </a>
    </div>

    <div class="madocs-card">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama/username/email" class="madocs-input py-2 sm:col-span-2">
            <select name="role" class="madocs-input py-2">
                <option value="">Semua Role</option>
                @foreach (\App\Models\User::ROLES as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['role'] ?? null) === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="madocs-input py-2">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(($filters['status'] ?? null) === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(($filters['status'] ?? null) === 'nonaktif')>Nonaktif</option>
            </select>
            <div class="sm:col-span-4 flex gap-2">
                <button type="submit" class="madocs-btn-primary">Terapkan Filter</button>
                <a href="{{ route('users.index') }}" class="madocs-btn-secondary">Reset Filter</a>
            </div>
        </form>
    </div>

    @if ($users->isEmpty())
        <div class="madocs-card flex flex-col items-center py-12 text-center">
            <p class="font-semibold text-slate-600">Belum ada pengguna yang sesuai</p>
            <p class="text-sm text-slate-400">Coba ubah filter pencarian atau tambahkan pengguna baru.</p>
        </div>
    @else
    <div class="madocs-card hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[800px] text-left text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-xs uppercase text-slate-400">
                    <th class="py-3 pr-4">Nama</th>
                    <th class="py-3 pr-4">Username</th>
                    <th class="py-3 pr-4">Role</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Dibuat</th>
                    <th class="py-3 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b border-slate-50 hover:bg-cream-50">
                        <td class="py-3 pr-4 font-semibold text-slate-700">{{ $user->name }}</td>
                        <td class="py-3 pr-4 text-slate-500">{{ $user->username }}</td>
                        <td class="py-3 pr-4"><span class="badge-blue">{{ $user->roleLabel() }}</span></td>
                        <td class="py-3 pr-4"><span class="{{ $user->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($user->status) }}</span></td>
                        <td class="py-3 pr-4 text-slate-500">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex flex-wrap justify-end gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.toggle-status', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">
                                            {{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('users.reset-password', $user) }}" onsubmit="return confirm('Reset kata sandi pengguna ini?');">
                                        @csrf
                                        <button type="submit" class="madocs-btn-secondary !px-3 !py-1.5 text-xs">Reset Password</button>
                                    </form>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="madocs-btn-danger !px-3 !py-1.5 text-xs">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-3 lg:hidden">
        @foreach ($users as $user)
            <div class="madocs-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-slate-700">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->username }} • {{ $user->roleLabel() }}</p>
                    </div>
                    <span class="{{ $user->status === 'aktif' ? 'badge-aktif' : 'badge-arsip' }}">{{ ucfirst($user->status) }}</span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="madocs-btn-secondary flex-1 !px-3 !py-1.5 text-xs">Edit</a>
                    @if ($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="madocs-btn-secondary w-full !px-3 !py-1.5 text-xs">{{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div>{{ $users->links() }}</div>
    @endif
</div>
@endsection

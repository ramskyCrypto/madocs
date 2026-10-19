<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->filled('q'), fn ($q) => $q->where(function ($qq) use ($request) {
                $term = $request->query('q');
                $qq->where('name', 'like', "%{$term}%")
                    ->orWhere('username', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            }))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->query('role')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->withCount('documents')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'filters' => $request->only(['q', 'role', 'status']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            ...$request->safe()->except('password'),
            'password' => Hash::make($request->validated('password')),
        ]);

        ActivityLogger::log('create_user', "Menambahkan pengguna \"{$user->name}\" ({$user->roleLabel()}).");

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        $user->update($data);

        ActivityLogger::log('edit_user', "Mengedit data pengguna \"{$user->name}\".");

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->status = $user->status === User::STATUS_AKTIF ? User::STATUS_NONAKTIF : User::STATUS_AKTIF;
        $user->save();

        ActivityLogger::log('edit_user', "Mengubah status pengguna \"{$user->name}\" menjadi \"{$user->status}\".");

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $newPassword = Str::random(10);
        $user->update(['password' => Hash::make($newPassword)]);

        ActivityLogger::log('edit_user', "Mereset kata sandi pengguna \"{$user->name}\".");

        return back()->with('success', "Kata sandi baru untuk {$user->name}: {$newPassword} (harap segera diganti oleh pengguna).");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if ($user->documents()->exists()) {
            // Jangan hapus permanen jika masih punya riwayat dokumen —
            // gunakan soft delete supaya data historis tidak rusak.
            $user->delete();
            ActivityLogger::log('delete_user', "Menonaktifkan (soft delete) pengguna \"{$user->name}\" karena masih memiliki riwayat dokumen.");

            return back()->with('success', 'Pengguna memiliki riwayat dokumen sehingga di-nonaktifkan permanen (soft delete), bukan dihapus total.');
        }

        $name = $user->name;
        $user->forceDelete();

        ActivityLogger::log('delete_user', "Menghapus permanen pengguna \"{$name}\".");

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}

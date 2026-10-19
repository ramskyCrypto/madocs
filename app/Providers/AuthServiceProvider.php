<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Document::class => DocumentPolicy::class,
        Category::class => CategoryPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gate tambahan untuk area "Sistem > Pengaturan" dan halaman
        // "Riwayat Aktivitas" yang khusus admin.
        Gate::define('manage-settings', fn (User $user) => $user->isAdmin());
        Gate::define('view-activities', fn (User $user) => $user->isAdmin());
    }
}

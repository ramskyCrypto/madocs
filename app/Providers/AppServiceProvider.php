<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Daftar kategori untuk sidebar (maks. 8, urut nama).
        View::composer('layouts.app', function ($view) {
            $view->with('sidebarCategories', Category::query()->orderBy('name')->limit(8)->get(['id', 'name']));
        });
    }
}

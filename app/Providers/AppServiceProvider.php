<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.store', function ($view): void {
            $view->with('navCategories', Category::query()->orderBy('sort_order')->take(8)->get());
            $view->with('cartCount', collect(session('cart', []))->sum());
        });
    }
}

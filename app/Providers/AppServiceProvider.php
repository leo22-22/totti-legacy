<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole() || !$this->app->bound('view')) {
            return;
        }

        View::composer('*', function ($view) {
            $cartService = app(CartService::class);
            $view->with('cartCount', $cartService->count());
        });
    }
}
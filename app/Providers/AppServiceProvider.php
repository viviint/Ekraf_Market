<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // <--- JANGAN LUPA IMPORT INI DI ATAS
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Definisikan aturan 'admin'
        Gate::define('admin', function (User $user) {
            // User dianggap admin jika role-nya 'admin'
            return $user->role === 'admin';
        });
        
        // Register observers
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
    }
}

<?php

namespace App\Providers;

use App\Observers\PalabraObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Palabra; 

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
        Palabra::observe(PalabraObserver::class);
    }
}

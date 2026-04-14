<?php

namespace App\Providers;

use App\Events\ReservaCreada;
use App\Listeners\EnviarCorreoReserva;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


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
        Schema::defaultStringLength(191);
        Event::listen(ReservaCreada::class, EnviarCorreoReserva::class);
    }
}

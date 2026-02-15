<?php

namespace App\Providers;

use App\Repositories\Auth\LoginInterface;
use App\Repositories\Auth\LoginRepository;
use App\Repositories\Auth\RegisterInterface;
use App\Repositories\Auth\RegisterRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            LoginInterface::class,
            LoginRepository::class
        );

        $this->app->bind(
            RegisterInterface::class,
            RegisterRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}

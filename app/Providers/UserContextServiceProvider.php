<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserContextService;
use App\Http\Controllers\EmployeeController;
use App\Services\JwtService;

class UserContextServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(JwtService::class, function ($app) {
            return new JwtService();
        });

        $this->app->singleton(EmployeeController::class, function ($app) {
            return new EmployeeController(
                $app->make(JwtService::class)
            );
        });

        $this->app->singleton(UserContextService::class, function ($app) {
            return new UserContextService(
                $app->make(EmployeeController::class)
            );
        });
    }

    public function boot()
    {
        //
    }

    public function provides()
    {
        return [
            'files',
            UserContextService::class,
            EmployeeController::class,
            JwtService::class
        ];
    }
}

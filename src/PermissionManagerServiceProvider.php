<?php

namespace Binssoft\Permissionmanager;

use Illuminate\Support\ServiceProvider;

class PermissionManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            // __DIR__.'/migrations' => base_path('database/migrations/'),
            __DIR__.'/models' => base_path('app/'),
            __DIR__.'/seeds' => base_path('database/seeds')
           
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

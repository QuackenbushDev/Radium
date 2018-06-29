<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Handle reverse proxy correctly
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            \URL::forceSchema($_SERVER['HTTP_X_FORWARDED_PROTO']);
        }
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('local') && isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $this->app['url']->forceScheme($_SERVER['HTTP_X_FORWARDED_PROTO']);
            $this->app['url']->forceRootUrl($_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['HTTP_X_ORIGINAL_HOST']);
        }
    }
}

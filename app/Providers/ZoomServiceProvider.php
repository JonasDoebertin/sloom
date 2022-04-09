<?php

namespace App\Providers;

use App\OAuth\Zoom\ZoomProvider;
use Illuminate\Support\ServiceProvider;

class ZoomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ZoomProvider::class, function ($app) {
            return new ZoomProvider([
                'clientId'     => config('services.zoom.client_id'),
                'clientSecret' => config('services.zoom.client_secret'),
                'redirectUri'  => route('zoom.callback'),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

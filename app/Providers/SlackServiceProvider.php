<?php

namespace App\Providers;

use App\OAuth\Slack\SlackProvider;
use Illuminate\Support\ServiceProvider;
use JoliCode\Slack\Client;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SlackProvider::class, function ($app) {
            return new SlackProvider([
                'clientId'     => config('services.slack.client_id'),
                'clientSecret' => config('services.slack.client_secret'),
                'redirectUri'  => route('slack.callback'),
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

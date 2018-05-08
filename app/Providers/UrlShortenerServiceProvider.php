<?php

namespace App\Providers;

use App\Contracts\UrlShortener\UrlShortener;
use App\Services\GoogleUrlShortenerService;
use Illuminate\Support\ServiceProvider;

class UrlShortenerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UrlShortener::class, function($app){
        	return new GoogleUrlShortenerService($app->make('Google_Client'), $app->make('\GuzzleHttp\Client'));
        });
    }
}

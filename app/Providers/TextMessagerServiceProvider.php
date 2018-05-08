<?php

namespace App\Providers;

use App\Contracts\TextMessage\TextMessager;
use App\Services\TextMessage\EzTextingService;
use App\Services\TextMessage\NexmoTextingService;
use Illuminate\Support\ServiceProvider;

class TextMessagerServiceProvider extends ServiceProvider
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
        $this->app->singleton(TextMessager::class, function($app){
           $guzzle = $app->makeWith('\GuzzleHttp\Client', ['config' => ['allow_redirects' => false, 'http_errors' => false]]);
           return new NexmoTextingService($guzzle, config('services.nexmo.key'), config('services.nexmo.secret'), config('services.nexmo.sms_from'));
        });
    }
}

<?php

namespace App\Providers;

use App\Components\ApiAuthComponet;
use App\Components\GoogleMapComponet;
use Illuminate\Support\ServiceProvider;

class ComponentProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api_auth', function () {
            return new ApiAuthComponet();
        });

        $this->app->bind('google_map', function () {
            return new GoogleMapComponet();
        });
    }
}

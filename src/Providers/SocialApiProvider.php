<?php

namespace SOS\SocialApi\Providers;

use Illuminate\Support\ServiceProvider;
use SOS\SocialApi\SocialApi;

/**
 * Class SocialApiProvider
 *
 * @author somar kesen
 * @package SOS\SocialApi\Providers
 */

class SocialApiProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialApi::class, function ($app) {
            return new SocialApi();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}

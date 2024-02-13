<?php

namespace SOS\SocialApi\Facades;

use Illuminate\Support\Facades\Facade;
use SOS\SocialApi\SocialApi;

/**
 * Facade for the SocialApi service.
 *
 * This class extends the Laravel Facade base class to provide a static interface
 * to the SocialApi service. It allows for easy access to the SocialApi functionality
 * throughout the application, using a static syntax.
 */
class SocialApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This method should return the service container binding key for the SocialApi service.
     * It tells Laravel what service the facade is providing access to.
     *
     * @return string The service container binding key
     */
    protected static function getFacadeAccessor()
    {
        // Return the fully qualified class name of the SocialApi service.
        // This assumes that the SocialApi service is bound in the service container using its class name.
        return SocialApi::class;
    }
}

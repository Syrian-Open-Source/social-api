<?php

namespace SOS\SocialApi\Facades;

use Illuminate\Support\Facades\Facade;
use SOS\SocialApi\SocialApi;

class SocialApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SocialApi::class;
    }
}

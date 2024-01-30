<?php

namespace SOS\SocialApi;

use SOS\SocialApi\Platforms\LinkedIn;
use SOS\SocialApi\Platforms\Google;
use SOS\SocialApi\Platforms\Facebook;
use SOS\SocialApi\Platforms\Github;
use InvalidArgumentException;

/**
 * @author by: Somar Kesen.
 * github: https://github.com/somarkn99
 * linkedin: https://www.linkedin.com/in/somarkesen/
 */
class SocialApi
{
    protected $platforms = [
        'Google' => Google::class,
        'Facebook' => Facebook::class,
        'Github' => Github::class,
        'LinkedIn' => LinkedIn::class,
    ];

    public function login($platformName, $token)
    {
        if (!array_key_exists($platformName, $this->platforms)) {
            throw new InvalidArgumentException("Unsupported platform: $platformName");
        }

        $platformClass = $this->platforms[$platformName];
        $platformInstance = new $platformClass($token);

        return $platformInstance->getUserInfo();
    }
}

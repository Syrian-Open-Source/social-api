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

/**
 * Core class for handling social media API interactions.
 *
 * This class provides a unified interface for logging into various social media platforms
 * and fetching user information by leveraging specific platform classes.
 */

class SocialApi
{
    // An associative array mapping platform names to their respective class implementations.
    protected $platforms = [
        'Google' => Google::class,
        'Facebook' => Facebook::class,
        'Github' => Github::class,
        'LinkedIn' => LinkedIn::class,
    ];

    /**
     * Attempts to log into a specified social media platform and fetch user information.
     *
     * @param string $platformName The name of the platform to log into.
     * @param string $token The authentication token for the platform.
     * @return array The user information fetched from the platform.
     * @throws InvalidArgumentException If the specified platform is not supported.
     */
    public function login($platformName, $token)
    {
        // Check if the specified platform is supported by looking it up in the platforms array.
        if (!array_key_exists($platformName, $this->platforms)) {
            // If not found, throw an exception indicating the platform is not supported.
            throw new InvalidArgumentException("Unsupported platform: $platformName");
        }

        // Retrieve the class name for the specified platform.
        $platformClass = $this->platforms[$platformName];
        // Instantiate the platform class, passing the token to its constructor.
        $platformInstance = new $platformClass($token);

        // Call the getUserInfo method on the platform instance to fetch user information.
        return $platformInstance->getUserInfo();
    }
}

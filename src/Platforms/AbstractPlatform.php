<?php

namespace SOS\SocialApi\Platforms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * @author by: Somar Kesen.
 * github: https://github.com/somarkn99
 * linkedin: https://www.linkedin.com/in/somarkesen/
 */


/**
 * An abstract base class designed for social media platform integration.
 * It handles common tasks such as making HTTP requests, managing tokens, and defining scopes.
 */
abstract class AbstractPlatform
{
    // Array to hold the scopes needed for the API request. Specific platforms can add their required scopes.
    protected $scopes = [];

    // The base URL for the API endpoint of the social media platform.
    protected $baseUrl;

    // Authentication token for API requests.
    protected $token;

    // HTTP client for making API requests.
    protected $httpClient;

    /**
     * Constructor to initialize the HTTP client and set the token.
     *
     * @param string|null $token The token for API authentication.
     */
    public function __construct($token = null)
    {
        $this->token = $token;
        $this->httpClient = new Client();
    }

    /**
     * Sets or updates the token used for API requests.
     *
     * @param string $token The token for API authentication.
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Adds a new scope for the API request if it's not already included.
     *
     * @param string $scope The scope to add.
     */
    public function addScope($scope)
    {
        if (!in_array($scope, $this->scopes)) {
            $this->scopes[] = $scope;
        }
    }

    /**
     * Sends an HTTP request to the specified endpoint using the Guzzle HTTP client.
     *
     * @param string $endpoint The API endpoint to request.
     * @param string $method The HTTP method (e.g., 'GET', 'POST').
     * @param array $headers Additional headers for the request.
     * @param mixed $body The request body or parameters.
     * @return array The decoded JSON response.
     * @throws \Exception If the HTTP request fails.
     */
    protected function sendRequest($endpoint, $method = 'GET', $headers = [], $body = [])
    {
        $options = [
            'headers' => array_merge(['Authorization' => 'Bearer ' . $this->token], $headers),
        ];

        if (!empty($body)) {
            if ($method === 'GET') {
                $options['query'] = $body;
            } else {
                $options['form_params'] = $body;
            }
        }

        try {
            $response = $this->httpClient->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw new \Exception("HTTP request failed: " . $e->getMessage());
        }
    }

    /**
     * Abstract method to fetch user information from the social media platform.
     * Must be implemented by the derived class.
     */
    abstract protected function getUserInfo();

    /**
     * Abstract method to map the raw user data fetched from the platform into a structured format.
     * Must be implemented by the derived class.
     *
     * @param array $scopes The scopes indicating which pieces of user information are requested.
     * @param array $userData The raw user data from the platform's API response.
     */
    abstract protected function mapUserDataByScopes($scopes, $userData);
}

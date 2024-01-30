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
 * Base class for social media platform integration.
 */

abstract class AbstractPlatform
{
  protected $scopes = [];
  protected $baseUrl;
  protected $token;
  protected $httpClient;

  public function __construct($token = null)
  {
    $this->token = $token;
    $this->httpClient = new Client();
  }

  public function setToken($token)
  {
    $this->token = $token;
  }

  public function addScope($scope)
  {
    if (!in_array($scope, $this->scopes)) {
      $this->scopes[] = $scope;
    }
  }

  protected function sendRequest($endpoint, $method = 'GET', $headers = [], $body = [])
  {
    try {
      $response = $this->httpClient->request($method, $this->baseUrl . $endpoint, [
        'headers' => array_merge(['Authorization' => 'Bearer ' . $this->token], $headers),
        'body' => $body
      ]);
      return json_decode($response->getBody(), true);
    } catch (GuzzleException $e) {
      throw new \Exception("HTTP request failed: " . $e->getMessage());
    }
  }

  abstract protected function getUserInfo();
  abstract protected function mapUserDataByScopes($scopes, $userData);
}

<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Http;

/**
 * @author by: Somar Kesen.
 * github: https://github.com/somarkn99
 * linkedin: https://www.linkedin.com/in/somarkesen/
 */
abstract class AbstractPlatform
{
  protected $scopes = [];
  protected $baseUrl;
  protected $token;

  public function __construct($token = null)
  {
    $this->token = $token;
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
    return Http::withToken($this->token)->get($this->baseUrl);
  }

  abstract protected function getUserInfo();
  abstract protected function mapUserDataByScopes($scopes ,$userData);
}

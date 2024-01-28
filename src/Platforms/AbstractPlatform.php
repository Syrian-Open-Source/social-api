<?php

namespace SOS\SocialApi\Platforms;

abstract class AbstractPlatform
{
  protected $scopes;
  protected $baseurl;


  abstract protected function getUserInfo($token);
  abstract protected function mapUserData($user);
  public function scopes($scopes)
  {
      $this->scopes = array_unique(array_merge($this->scopes, (array) $scopes));

      return $this;
  }
}

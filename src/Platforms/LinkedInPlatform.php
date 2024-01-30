<?php

namespace SOS\SocialApi\Platforms;

/**
 * @author by:Ali Alshikh.
 * github: https://github.com/AliAlshikh99
 * linkedin:https://www.linkedin.com/in/ali-alshikh99/ 
 */
class LinkedIn extends AbstractPlatform 
{
    protected $scopes = ['openid', 'profile', 'email'];
    protected $baseUrl = 'https://api.linkedin.com/v2/userinfo';

    public function getUserInfo()
    {
        try {
            $response = $this->sendRequest($this->baseUrl);
            if ($response->successful()) {
                return $this->mapUserDataByScopes(['profile'],$response->json());
            }
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            throw new \Exception('LinkedIn API Error: ' . $th->getMessage());
        }
    }

  public function mapUserDataByScopes($scopes,$userData)
    {
    
        $mappedData = [];

        if ($scopes === []) {
            $mappedData['data'] = $userData->json();
        } else {
            $requiredFields = [];
            foreach ($scopes as $scope) {

                $requiredFields = array_merge($requiredFields, match ($scope)
                {
                    'openid' => ['id'],
                    'email' => ['id', 'email'],
                    'profile' => ['id', 'first_name', 'last_name', 'email', 'profile_image'],
                }
            );
        }

        $mappedData = array_intersect_key([
            'id'=> $userData['sub'],
            'email'=>$userData['email'],
            'first_name'=>$userData['given_name'],
            'last_name'=>$userData['family_name'],
            'profile_image'=>$userData['picture'],


        ], array_flip($requiredFields));
    }

       return $mappedData;
    }

}

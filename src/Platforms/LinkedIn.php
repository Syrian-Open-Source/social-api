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
            $response = $this->sendRequest($this->baseUrl, 'GET');
            if (isset($response['id'])) {
                return $this->mapUserDataByScopes($this->scopes, $response);
            }
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            throw new \Exception('LinkedIn API Error: ' . $th->getMessage());
        }
    }


    public function mapUserDataByScopes($scopes, $userData)
    {
        $mappedData = [];
        $requiredFields = [];

        foreach ($scopes as $scope) {
            switch ($scope) {
                case 'openid':
                    $requiredFields = array_merge($requiredFields, ['id']);
                    break;
                case 'email':
                    $requiredFields = array_merge($requiredFields, ['id', 'emailAddress']);
                    break;
                case 'profile':
                    $requiredFields = array_merge($requiredFields, ['id', 'firstName', 'lastName', 'profilePicture']);
                    break;
            }
        }

        foreach ($requiredFields as $field) {
            $mappedData[$field] = $userData[$field] ?? null;
        }

        return $mappedData;
    }
}

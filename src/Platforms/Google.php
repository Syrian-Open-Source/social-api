<?php

namespace SOS\SocialApi\Platforms;

/**
 * @author by:Mary Ali.
 * github: https://github.com/Marikamal
 * linkedin:https://www.linkedin.com/in/mary-kamal-ali/
 */

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Google extends AbstractPlatform
{
    // Google specific logic and data
    protected $scopes = [
        'openid', 
        'profile', 
        'email', 
    ];
    protected $baseUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function getUserInfo()
    {
        try {
            $response = $this->sendRequest($this->baseUrl, 'GET');
            if (isset($response['sub'])) {
                return $this->mapUserDataByScopes($this->scopes, $response);
            }
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            Log::error('Failed to retrieve user information from Google API: ' . $e->getMessage());
            throw new \Exception('Google API Error: ' . $th->getMessage());
        }
    }

    public function mapUserDataByScopes($scopes,$userData)
    { 

        $mappedData = [];
        $requiredFields = [];
        $fieldMappings = [
            'sub' => 'id',
            'email' => 'emailAddress',
            'given_name' => 'firstName',
            'family_name' => 'lastName',
            'picture' => 'profilePicture'
        ];
      
        if ($scopes !== null) {
            foreach ($scopes as $scope) {
                switch ($scope) {
                    case 'openid':
                        $requiredFields = array_merge($requiredFields, ['sub']);
                        break;
                    case 'email':
                        $requiredFields = array_merge($requiredFields, ['sub', 'email']);
                        break;
                    case 'profile':
                        $requiredFields = array_merge($requiredFields, ['sub', 'given_name', 'family_name', 'picture']);
                        break;
                    default:
                        throw new \Exception('Invalid scope: ' . $scope);
                }
            }
        }
        // Add default required fields if none of the scopes match
        if (empty($requiredFields)) {
            $requiredFields = ['sub', 'email', 'given_name', 'family_name', 'picture'];
        }
    
        foreach ($requiredFields as $requiredField) {
            $fieldName = $fieldMappings[$requiredField] ?? $requiredField;
            $mappedData[$fieldName] = $userData[$requiredField] ?? null;
        }
        return $mappedData;
    }
}
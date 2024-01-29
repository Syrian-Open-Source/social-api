<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Google extends AbstractPlatform
{
    // Google specific logic and data
    protected $scopes = [
        'openid', 
        'user_profile', 
        'user_email', 
    ];
    protected $baseUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function getUserInfo()
    {
        try{
            $response = $this->sendRequest($this->baseUrl);
            $response->throw();
            return $response->json(); 
       }
       catch(\Exception $e){  
            Log::error('Failed to retrieve user information from Google API: ' . $e->getMessage());
       }
    }

    public function mapUserDataByScopes($data)
    {
        // Mapping user information      
        try{
            return [
                'id' => $data['sub'],
                'nickname' => $data['given_name'], 
                'name' => $data['name'],
                'email' => $data['email'],
            ];
        }
        catch(\Exception $e){          
            Log::error('An exception occurred while mapping user information:  ' . $e->getMessage());   
        }
    }
}
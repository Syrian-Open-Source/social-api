<?php

namespace SOS\SocialApi\Platforms;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Google extends AbstractPlatform
{
    // Google specific logic and data


    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid', 
        'user_profile', 
        'user_email', 
    ];

    protected $baseurl = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function getUserInfo($token)
    {
        try{
            $data = Http::withToken($token)
                ->acceptJson()
                ->get($this->baseurl);
            $data->throw();
            return $data->json();   
       }

       catch(\Exception $e){  
            Log::error('Failed to retrieve user information from Google API: ' . $e->getMessage());
       }
        
    
    }

    public function mapUserData($data)
    {
        // Mapping user information
        
        try{
            return [
                'id' => $data['sub'],
                'nickname' => $data['given_name'], 
                'name' => $data['name'],
                'email' => $data['email'],
                'profile_image' => $data['picture'],
            ];
        }
        catch(\Exception $e){
            
            Log::error('An exception occurred while mapping user information:  ' . $e->getMessage());
          

    }
}





























}
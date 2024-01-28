<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Http;

class LinkedIn extends AbstractPlatform
{
    protected $scopes = ['openid', 'profile', 'email'];

    protected $baseurl = 'https://api.linkedin.com/v2/userinfo';

    public function getUserInfo($token)
    {
        $data = Http::withToken($token)->get($this->baseurl);
        try {
            return $data->json();
        } catch (\Throwable $th) {
            log::error('Error Message !!');
        }
    }
    public function mapUserData($data){
        $info=collect(['data'=>$data])->map(function($user){
                return[
                    'id'=>$user['sub'],
                    'first_name'=>$user['given_name'],
                    'last_name'=>$user['family_name'],
                    'email'=>$user['email'],
                    'profile_image'=>$user['picture']
    
    
                ];
            });
            return $info;
    
    }
}

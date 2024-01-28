<?php

namespace SOS\SocialApi\Platforms;

/**
 * arthur by: Ali.
 * github: 
 * linkedin: 
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
                return $this->mapUserData($response->json());
            }
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            throw new \Exception('LinkedIn API Error: ' . $th->getMessage());
        }
    }

    public function mapUserDataByScopes($data)
    {
        return [
            'id' => $data->sub,
            'first_name' => $data->given_name,
            'last_name' => $data->family_name,
            'email' => $data->email,
            'profile_image' => $data->picture
        ];
    }
}

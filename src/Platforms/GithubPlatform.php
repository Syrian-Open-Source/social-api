<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Log;

class GithubPlatform extends AbstractPlatform
{

    protected $baseurl = 'https://api.github.com/user';
    protected $scopes;

    public function getUserInfo($token)
    {
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get($this->baseurl);

            $user = json_decode($response->getBody(), true);

            $mappedData = $this->mapUserToObject($user);

            return response()->json(['Data' => $mappedData]);

        } catch (\Exception $e) {

            Log::error('Failed To Fetch User Information: ' . $e->getMessage());

        }
    }

    protected function mapUserData( $user)
    {
        return ([
            'id' => $user['id'],
            'nodeId' => $user['node_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar_url' => $user['avatar_url'],

        ]);
    }
}
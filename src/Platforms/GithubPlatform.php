<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Log;

class Github extends AbstractPlatform
{
    protected $baseUrl = 'https://api.github.com/user';
    protected $scopes = [];

    public function getUserInfo()
    {
        try {
            $response = $this->sendRequest($this->baseUrl);
            if (!$response->successful()) {
                throw new \Exception('API request failed.');
            }
            $user = $response->json();
            $mappedData = $this->mapUserData($user);
            return response()->json(['Data' => $mappedData]);
        } catch (\Exception $e) {
            Log::error('Failed To Fetch User Information: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function mapUserDataByScopes($user)
    {
        return [
            'id' => $user['id'] ?? null,
            'nodeId' => $user['node_id'] ?? null,
            'name' => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
            'avatar_url' => $user['avatar_url'] ?? null,
        ];
    }
}

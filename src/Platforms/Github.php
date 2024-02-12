<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * arthur by: Homam haidar.
 * github:https://github.com/HomamHaidar
 * linkedin:https://www.linkedin.com/in/homamhaidar/
 */
class Github extends AbstractPlatform
{
    protected $baseUrl = 'https://api.github.com/user';
    protected $scopes = ['id', 'nodeId', 'nickname', 'name', 'email', 'avatar'];


    public function getUserInfo()
    {
        try {
            $response = $this->sendRequest($this->baseUrl);
            if (isset($response['id'])) {
                return $this->mapUserDataByScopes($this->scopes, $response);
            }
            throw new \Exception('Failed to fetch user info');
        } catch (\Exception $e) {
            Log::error('Failed To Fetch User Information: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function mapUserDataByScopes($scopes, $userData)
    {
        $mappedData = [];
        $requiredFields = [];

        foreach ($scopes as $scope) {
            $requiredFields[] = 'id';
            if (in_array($scope, $this->scopes)) {
                $requiredFields[] = $scope;
            }
        }

        if (empty($requiredFields)) {
            $requiredFields = $this->scopes;
        }

        foreach ($requiredFields as $field) {
            $mappedData[$field] = $userData[$field] ?? null;
        }

        return $mappedData;
    }
}

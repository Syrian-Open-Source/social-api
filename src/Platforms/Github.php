<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * arthur by: Homam haidar.
 * github:https://github.com/HomamHaidar
 * linkedin:https://www.linkedin.com/in/homamhaidar/
 */
/**
 * A class for integrating with GitHub's API to fetch user information.
 * It extends the AbstractPlatform, indicating it shares common functionality with other social platform integrations.
 */

class Github extends AbstractPlatform
{
    // The base URL for GitHub's user API endpoint.
    protected $baseUrl = 'https://api.github.com/user';

    // An array of scopes representing the pieces of user information this class can handle.
    protected $scopes = ['id', 'nodeId', 'nickname', 'name', 'email', 'avatar'];

    /**
     * Fetches user information from GitHub.
     *
     * @return array An associative array containing mapped user data.
     * @throws \Exception if there is an issue fetching the user information.
     */
    public function getUserInfo()
    {
        try {
            // Send a request to the GitHub API endpoint and store the response.
            $response = $this->sendRequest($this->baseUrl);

            // Check if the response contains an 'id', indicating successful retrieval of user data.
            if (isset($response['id'])) {
                // Map the raw API response data to a structured format based on predefined scopes.
                return $this->mapUserDataByScopes($this->scopes, $response);
            }

            // If no 'id' is present in the response, throw an exception indicating failure to fetch user info.
            throw new \Exception('Failed to fetch user info');
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes.
            Log::error('Failed To Fetch User Information: ' . $e->getMessage());
            // Rethrow the exception to be handled by the caller.
            throw $e;
        }
    }

    /**
     * Maps raw user data from GitHub's API response to a structured array based on specified scopes.
     *
     * @param array $scopes The scopes indicating which pieces of user information are requested.
     * @param array $userData The raw user data from GitHub's API response.
     * @return array The mapped user data.
     */
    protected function mapUserDataByScopes($scopes, $userData)
    {
        $mappedData = [];
        $requiredFields = [];

        // Iterate through the requested scopes and add them to the list of required fields.
        foreach ($scopes as $scope) {
            $requiredFields[] = 'id'; // Ensure 'id' is always included.
            if (in_array($scope, $this->scopes)) {
                $requiredFields[] = $scope;
            }
        }

        // Fallback to default scopes if no specific fields are required.
        if (empty($requiredFields)) {
            $requiredFields = $this->scopes;
        }

        // Map each required field from the raw API response to the structured mapped data array.
        foreach ($requiredFields as $field) {
            $mappedData[$field] = $userData[$field] ?? null;
        }

        return $mappedData;
    }
}

<?php

namespace SOS\SocialApi\Platforms;

/**
 * @author by:Mary Ali.
 * github: https://github.com/Marikamal
 * linkedin:https://www.linkedin.com/in/mary-kamal-ali/
 */

use Illuminate\Support\Facades\Log;

/**
 * Integration class for fetching user information from Google's API.
 *
 * Utilizes scopes to request specific user data and maps the API response
 * to a structured format.
 */
class Google extends AbstractPlatform
{
    // Specifies the scopes that will be requested from Google's API.
    protected $scopes = [
        'openid',
        'profile',
        'email',
    ];

    // Base URL for fetching user information from Google.
    protected $baseUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';

    /**
     * Fetches user information from Google using OAuth 2.0 credentials.
     *
     * @return array An array containing mapped user data.
     * @throws \Exception if the user information cannot be fetched.
     */
    public function getUserInfo()
    {
        try {
            // Sends a GET request to the Google API and stores the response.
            $response = $this->sendRequest($this->baseUrl, 'GET');

            // Checks if the response contains the user identifier ('sub').
            if (isset($response['sub'])) {
                // Maps the API response to a structured format based on requested scopes.
                return $this->mapUserDataByScopes($this->scopes, $response);
            }

            // Throws an exception if the user identifier is missing in the response.
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            // Logs any errors encountered during the API request.
            Log::error('Failed to retrieve user information from Google API: ' . $th->getMessage());
            // Rethrows the exception with a custom message.
            throw new \Exception('Google API Error: ' . $th->getMessage());
        }
    }

    /**
     * Maps raw user data from Google's API to a structured array based on requested scopes.
     *
     * @param array $scopes The scopes indicating which pieces of user information are requested.
     * @param array $userData The raw user data from Google's API response.
     * @return array The mapped user data.
     */
    public function mapUserDataByScopes($scopes, $userData)
    {
        $mappedData = [];
        $requiredFields = [];

        // Maps API response fields to more friendly names.
        $fieldMappings = [
            'sub' => 'id',
            'email' => 'email',
            'given_name' => 'firstName',
            'family_name' => 'lastName',
            'picture' => 'picture'
        ];

        // Determines which fields to include in the mapped data based on requested scopes.
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
                    // Throws an exception for any unrecognized scopes.
                    throw new \Exception('Invalid scope: ' . $scope);
            }
        }

        // If no specific scopes were requested, include all default fields.
        if (empty($requiredFields)) {
            $requiredFields = ['sub', 'email', 'given_name', 'family_name', 'picture'];
        }

        // Maps each required field from the raw API response to the structured mapped data array.
        foreach ($requiredFields as $requiredField) {
            $fieldName = $fieldMappings[$requiredField] ?? $requiredField;
            $mappedData[$fieldName] = $userData[$requiredField] ?? null;
        }

        return $mappedData;
    }
}

<?php

namespace SOS\SocialApi\Platforms;

use Illuminate\Support\Facades\Log;

/**
 * @author by:Ali Alshikh.
 * github: https://github.com/AliAlshikh99
 * linkedin:https://www.linkedin.com/in/ali-alshikh99/
 */


/**
 * LinkedIn platform integration class.
 * This class extends the AbstractPlatform and implements methods to fetch user information from LinkedIn.
 * It utilizes LinkedIn's API to retrieve user details based on specified scopes.
 */

class LinkedIn extends AbstractPlatform
{
    // Define the scopes that will be used to request user information. These should align with LinkedIn API permissions.
    protected $scopes = ['openid', 'name', 'email', 'picture'];

    // Base URL for LinkedIn's API to fetch user information.
    protected $baseUrl = 'https://api.linkedin.com/v2/userinfo';

    /**
     * Fetches user information from LinkedIn.
     *
     * @return array Mapped user data based on the requested scopes.
     * @throws \Exception if there's an error fetching user information or if required data is missing.
     */
    public function getUserInfo()
    {
        try {
            // Send a GET request to LinkedIn API and store the response.
            $response = $this->sendRequest($this->baseUrl, 'GET');

            // Check if the expected user identifier 'sub' is present in the response.
            if (isset($response['sub'])) {
                // Map the raw API response data to a structured format based on the specified scopes.
                return $this->mapUserDataByScopes($this->scopes, $response);
            }

            // Throw an exception if the 'sub' identifier is not found, indicating missing user info.
            throw new \Exception('Failed to fetch user info');
        } catch (\Throwable $th) {
            // Catch any exceptions or errors, log them, and rethrow a more generic exception to avoid leaking sensitive information.
            throw new \Exception('LinkedIn API Error: ' . $th->getMessage());
        }
    }

    /**
     * Maps the raw user data from LinkedIn's API to a structured array based on the required fields.
     *
     * @param array $scopes The scopes indicating which pieces of user information are requested.
     * @param array $userData The raw user data from LinkedIn's API response.
     * @return array The mapped user data including only the fields specified by the requested scopes.
     */
    public function mapUserDataByScopes($scopes, $userData)
{
    // Initialize an empty array to store the mapped data
    $mappedData = [];

    // Initialize an empty array to store the required fields
    $requiredFields = [];

    // Loop through each scope
    foreach ($scopes as $scope) {
        // Use a match expression to determine the required fields based on the scope
        // and merge them into the $requiredFields array
        $requiredFields = array_merge($requiredFields, match ($scope) {
            'openid' => ['sub' => 'id'],
            'email' => ['email' => 'email'],
            'name' => ['name' => 'name'],
            'picture' => ['picture' => 'picture'],
        });
    }

    // Loop through each required field
    foreach ($requiredFields as $apiField => $field) {
        // Map the corresponding value from $userData to $mappedData,
        // or set it to null if it doesn't exist in $userData
        $mappedData[$field] = $userData[$apiField] ?? null;
    }

    // Return the mapped data
    return $mappedData;
}
}

<?php

namespace App\src\Platforms;

use Exception;

/**
 * @author by:Mustafa Fares.
 * GitHub: https://github.com/MustafaFares445
 * LinkedIn:https://www.linkedin.com/in/mustafa-fares/
 */
class Facebook extends AbstractPlatform
{
    protected $scopes = ['id', 'name', 'first_name', 'last_name', 'email', 'gender', 'picture'];
    protected $baseUrl = 'https://graph.facebook.com/me';

    /**
     * @throws Exception
     */
    public function getUserInfo(): array
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


    public function mapUserDataByScopes($scopes, $userData): array
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

<?php

// Example helper function to format API responses
function formatApiResponse($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}

// Example helper function to log API errors
function logApiError($error)
{
    error_log($error, 3, "path/to/your/log/file.log");
}

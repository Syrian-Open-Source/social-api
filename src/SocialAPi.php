<?php

namespace SOS\SocialApi;

use Illuminate\Support\Facades\Validator;

/**
 * @author by: Somar Kesen.
 * github: https://github.com/somarkn99
 * linkedin: https://www.linkedin.com/in/somarkesen/
 */
class SocialApi
{
    public function Login($platform)
    {
        $validator = Validator::make(['platform' => $platform], [
            'platform' => 'required|in:Google,Facebook,Github,LinkedIn',
        ]);

        if ($validator->fails()) {
            // Handle the error scenario
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors());
        }
    }
}

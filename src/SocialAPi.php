<?php

namespace SOS\SocialApi;

use Illuminate\Support\Facades\Validator;

class SocialApi
{
    public function Login($platform)
    {
        // You Logic Should be here...
        $validator = Validator::make(['platform' => $platform], [
            'platform' => 'required|in:Google,Facebook,Github,LinkedIn',
        ]);

        if ($validator->fails()) {
            // Handle the error scenario
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors());
        }
    }
}

<?php

namespace App\Documentation\Paths;

use App\Documentation\Components\Schemas\JwtToken;
use App\Documentation\Components\Schemas\UserCredentials;
use ArrayObject;

class LoginCheck implements PathInterface
{
    public function getPath(): string
    {
        return '/api/login-check';
    }

    public function getMethod(): string
    {
        return 'post';
    }

    public function getParams(): ArrayObject
    {
        return new ArrayObject([
            'tags' => ['User'],
            'summary' => 'Get JWT token to login by Email and Password (Authorize user).',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => (new UserCredentials())->getRef(),
                        ],
                    ],
                ],
            ],
            'responses' => [
                200 => [
                    'description' => 'Return JWT token',
                    'schema' => [
                        '$ref' => (new JwtToken())->getRef(),
                    ],
                ],
                401 => [
                    'description' => 'Bad credentials',
                ],
            ],
        ]);
    }
}

<?php

namespace App\Documentation\Paths;

use App\Documentation\Components\Schemas\JwtToken;
use App\Documentation\Components\Schemas\Message;
use ArrayObject;

/**
 * Class ValidatePasswordRecoveryToken
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Documentation\Paths
 */
class ValidatePasswordRecoveryToken implements PathInterface, ReplacePathInterface
{
    /**
     * Get path
     */
    public function getPath(): string
    {
        return '/api/forgot-password/validate-token';
    }

    /**
     * Get http method
     */
    public function getMethod(): string
    {
        return 'post';
    }

    /**
     * Get params
     */
    public function getParams(): ArrayObject
    {
        return new ArrayObject([
            'tags' => ['User'],
            'summary' => 'Validate password recovery token.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'name' => 'token',
                        'in' => 'body',
                        'schema' => [
                            'type' => 'object',
                            'description' => '',
                            'properties' => [
                                'token' => [
                                    'type' => 'string',
                                    'description' => 'token',
                                    'required' => true,
                                ],
                            ],
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
                404 => [
                    'description' => 'Invalid code',
                    'schema' => [
                        '$ref' => (new Message())->getRef(),
                    ],
                ],
            ],
        ]);
    }
}

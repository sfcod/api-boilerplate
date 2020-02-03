<?php

namespace App\Documentation\Paths;

use App\Documentation\Definitions\JwtToken;
use App\Documentation\Definitions\Message;
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
        return '/api/forgot-password/validate-token/{token}';
    }

    /**
     * Get http method
     */
    public function getMethod(): string
    {
        return 'get';
    }

    /**
     * Get params
     */
    public function getParams(): ArrayObject
    {
        return new ArrayObject([
            'tags' => ['User'],
            'summary' => 'Validate password recovery token.',
            'parameters' => [
                [
                    'name' => 'token',
                    'in' => 'path',
                    'description' => 'Recovery token from email',
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

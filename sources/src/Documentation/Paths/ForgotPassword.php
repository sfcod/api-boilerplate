<?php

namespace App\Documentation\Paths;

use App\Documentation\Components\Schemas\Message;
use ArrayObject;

/**
 * Class ForgotPassword
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Documentation\Paths
 */
class ForgotPassword implements PathInterface, ReplacePathInterface
{
    /**
     * Get path
     */
    public function getPath(): string
    {
        return '/api/forgot-password';
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
            'summary' => 'Recovery password request.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'name' => 'username',
                        'in' => 'body',
                        'schema' => [
                            'type' => 'object',
                            'description' => '',
                            'properties' => [
                                'username' => [
                                    'type' => 'string',
                                    'description' => 'username or email',
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'responses' => [
                200 => [
                    'description' => 'Recovery password email was sent',
                    'schema' => [
                        '$ref' => (new Message())->getRef(),
                    ],
                ],
                404 => [
                    'description' => 'User not found',
                    'schema' => [
                        '$ref' => (new Message())->getRef(),
                    ],
                ],
            ],
        ]);
    }
}

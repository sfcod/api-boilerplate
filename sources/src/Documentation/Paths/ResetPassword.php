<?php

namespace App\Documentation\Paths;

use ArrayObject;

/**
 * Class ResetPassword
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Documentation\Paths
 */
class ResetPassword implements PathInterface, ReplacePathInterface
{
    /**
     * Get path
     */
    public function getPath(): string
    {
        return '/api/users/reset-password';
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
            'summary' => 'Reset password.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'name' => 'password',
                        'in' => 'body',
                        'schema' => [
                            'type' => 'object',
                            'description' => '',
                            'properties' => [
                                'password' => [
                                    'type' => 'string',
                                    'description' => 'plain password',
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'responses' => [
                204 => [
                    'description' => 'Password was updated successfully',
                ],
            ],
        ]);
    }
}

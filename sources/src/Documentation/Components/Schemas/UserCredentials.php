<?php

namespace App\Documentation\Components\Schemas;

use App\Documentation\Components\SchemaInterface;

class UserCredentials implements SchemaInterface
{
    public function getSchema(): array
    {
        return [
            'type' => 'object',
            'description' => '',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'description' => 'username or email',
                    'required' => true,
                ],
                'password' => [
                    'type' => 'string',
                    'description' => 'plain password',
                    'required' => true,
                ],
            ],
        ];
    }

    public function getRef(): string
    {
        return '#/components/schemas/' . $this->getName();
    }

    public function getName(): string
    {
        return 'User:credentials';
    }
}

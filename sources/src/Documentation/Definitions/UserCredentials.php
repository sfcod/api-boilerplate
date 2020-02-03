<?php

namespace App\Documentation\Definitions;

class UserCredentials implements DefinitionObjectInterface
{
    public function getName(): string
    {
        return 'User:credentials';
    }

    public function getParams(): array
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
        return '#/definitions/' . $this->getName();
    }
}

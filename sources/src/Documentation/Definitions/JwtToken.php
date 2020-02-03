<?php

namespace App\Documentation\Definitions;

class JwtToken implements DefinitionObjectInterface
{
    public function getName(): string
    {
        return 'Token';
    }

    public function getParams(): array
    {
        return [
            'type' => 'object',
            'description' => '',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];
    }

    public function getRef(): string
    {
        return '#/definitions/Token';
    }
}

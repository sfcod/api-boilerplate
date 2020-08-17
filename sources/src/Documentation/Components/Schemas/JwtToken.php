<?php

namespace App\Documentation\Components\Schemas;

use App\Documentation\Components\SchemaInterface;

class JwtToken implements SchemaInterface
{
    public function getName(): string
    {
        return 'Token';
    }

    public function getSchema(): array
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
        return '#/components/schemas/Token';
    }
}

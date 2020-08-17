<?php

namespace App\Documentation\Components\Schemas;

use App\Documentation\Components\SchemaInterface;

class VerifyEmailCode implements SchemaInterface
{
    public function getName(): string
    {
        return 'VerifyEmailCode';
    }

    public function getSchema(): array
    {
        return [
            'type' => 'object',
            'description' => 'Verify email code. Length is 6.',
            'properties' => [
                'emailConfirmPin' => [
                    'type' => 'string',
                    'readOnly' => true,
                    'minimum' => 6,
                    'maximum' => 6,
                ],
            ],
        ];
    }

    public function getRef(): string
    {
        return '#/components/schemas/VerifyEmailCode';
    }
}

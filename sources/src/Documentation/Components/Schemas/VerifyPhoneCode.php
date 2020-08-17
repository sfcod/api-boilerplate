<?php

namespace App\Documentation\Components\Schemas;

use App\Documentation\Components\SchemaInterface;

class VerifyPhoneCode implements SchemaInterface
{
    public function getName(): string
    {
        return 'VerifyPhoneCode';
    }

    public function getSchema(): array
    {
        return [
            'type' => 'object',
            'description' => 'Verify email code. Length is 4.',
            'properties' => [
                'phoneConfirmPin' => [
                    'type' => 'string',
                    'readOnly' => true,
                    'minimum' => 4,
                    'maximum' => 4,
                ],
            ],
        ];
    }

    public function getRef(): string
    {
        return '#/components/schemas/VerifyPhoneCode';
    }
}

<?php

namespace App\Documentation\Components\Schemas;

use App\Documentation\Components\SchemaInterface;

/**
 * Class Message
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Documentation\Definitions
 */
class Message implements SchemaInterface
{
    public function getName(): string
    {
        return 'Message';
    }

    public function getSchema(): array
    {
        return [
            'type' => 'object',
            'description' => '',
            'properties' => [
                'message' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];
    }

    public function getRef(): string
    {
        return '#/components/schemas/Message';
    }
}

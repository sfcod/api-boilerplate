<?php

namespace App\Documentation\Definitions;

/**
 * Class Message
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Documentation\Definitions
 */
class Message implements DefinitionObjectInterface
{
    public function getName(): string
    {
        return 'Message';
    }

    public function getParams(): array
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
        return '#/definitions/Message';
    }
}

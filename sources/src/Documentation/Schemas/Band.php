<?php

namespace App\Documentation\Schemas;

class Band implements SchemasInterface
{
    public function getProperties(): array
    {
        return [
            'customField' => [
                'readOnly' => true,
                'type' => 'integer',
            ],
        ];
    }

    public function getSchemas(): array
    {
        return ['Band'];
    }
}

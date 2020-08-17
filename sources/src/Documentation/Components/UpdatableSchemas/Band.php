<?php

namespace App\Documentation\Components\UpdatableSchemas;

use App\Documentation\Components\UpdatableSchemaInterface;

class Band implements UpdatableSchemaInterface
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

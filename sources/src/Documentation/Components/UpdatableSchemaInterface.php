<?php

namespace App\Documentation\Components;

/**
 * Interface SchemasInterface
 *
 * @package App\Documentation\schemas
 */
interface UpdatableSchemaInterface
{
    public function getProperties(): array;

    public function getSchemas(): array;
}

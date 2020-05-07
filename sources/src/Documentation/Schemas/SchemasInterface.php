<?php

namespace App\Documentation\Schemas;

/**
 * Interface SchemasInterface
 *
 * @package App\Documentation\schemas
 */
interface SchemasInterface
{
    public function getProperties(): array;

    public function getSchemas(): array;
}

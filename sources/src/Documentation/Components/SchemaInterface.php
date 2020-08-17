<?php

namespace App\Documentation\Components;

/**
 * Interface DefinitionInterface
 *
 * @package App\Documentation\definitions
 */
interface SchemaInterface
{
    public function getName(): string;

    public function getSchema(): array;

    public function getRef(): string;
}

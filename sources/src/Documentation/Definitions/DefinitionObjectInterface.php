<?php

namespace App\Documentation\Definitions;

/**
 * Interface DefinitionInterface
 *
 * @package App\Documentation\definitions
 */
interface DefinitionObjectInterface
{
    public function getName(): string;

    public function getParams(): array;

    public function getRef(): string;
}

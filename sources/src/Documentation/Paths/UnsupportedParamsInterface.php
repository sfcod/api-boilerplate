<?php

namespace App\Documentation\Paths;

interface UnsupportedParamsInterface
{
    public function getUnusedParams(): array;
}

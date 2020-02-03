<?php

namespace App\Documentation\Paths;

use ArrayObject;

/**
 * Interface PathInterface
 *
 * @package App\Documentation\paths
 */
interface PathInterface
{
    /**
     * Get path
     */
    public function getPath(): string;

    /**
     * Get http method
     */
    public function getMethod(): string;

    /**
     * Get params
     */
    public function getParams(): ArrayObject;
}

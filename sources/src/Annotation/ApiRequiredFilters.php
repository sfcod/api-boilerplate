<?php

namespace App\Annotation;

/**
 * Class ApiRequiredFilters
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\Annotation
 */
class ApiRequiredFilters
{
    /**
     * @Required()
     */
    public string $action;

    /**
     * @Required()
     */
    public array $filters = [];
}

<?php

namespace App\Annotation;

/**
 * AgrNormalizer.
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class AgrNormalizer
{
    /**
     * @Required()
     */
    public string $normalizer;

    /**
     * @Required()
     */
    public array $groups = [];
}

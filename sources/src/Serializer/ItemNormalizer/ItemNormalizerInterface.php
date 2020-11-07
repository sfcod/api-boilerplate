<?php

namespace App\Serializer\ItemNormalizer;

/**
 * Interface ItemNormalizerInterface
 *
 * @package App\Serializer\ItemNormalizer
 *
 * @author Dmitry Turchanin
 */
interface ItemNormalizerInterface
{
    /**
     * Normalize data
     *
     * @param mixed $entity
     */
    public function normalize($entity, array &$data, array $context);
}

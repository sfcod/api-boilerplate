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
     * @param object $entity
     */
    public function normalize($entity, array &$data);

    /**
     * @param object $object
     */
    public function supports($object): bool;
}

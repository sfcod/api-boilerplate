<?php

namespace App\Serializer\ItemNormalizer;

use App\Entity\User;

/**
 * Class UserItemNormalizer
 *
 * @package App\Serializer\ItemNormalizer
 */
class UserItemNormalizer implements ItemNormalizerInterface
{
    /**
     * Normalize data
     *
     * @param User $entity
     *
     * @return void
     */
    public function normalize($entity, array &$data)
    {
    }

    /**
     * @param object $object
     */
    public function supports($object): bool
    {
        return $object instanceof User;
    }
}

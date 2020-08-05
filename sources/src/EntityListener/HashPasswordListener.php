<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoder;

/**
 * Class HashPasswordListener
 *
 * @package App\EntityListener
 */
class HashPasswordListener
{
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     *
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param LifecycleEventArgs $args
     */
    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        $this->encodePassword($user);
    }

    /**
     * @param User $user
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(User $user, PreUpdateEventArgs $args)
    {
        $this->encodePassword($user);
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity)
    {
        if (!$entity->getPlainPassword()) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );

        $entity->setPassword($encoded);
    }
}

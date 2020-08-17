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
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        $this->encodePassword($user);
    }

    public function preUpdate(User $user, PreUpdateEventArgs $args)
    {
        $this->encodePassword($user);
    }

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

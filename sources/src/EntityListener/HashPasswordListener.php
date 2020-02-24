<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoder;

class HashPasswordListener
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(User $user, LifecycleEventArgs $args)
    {
        if (!$user->getPlainPassword()) {
            return;
        }
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

    public function preUpdate(User $user, LifecycleEventArgs $args)
    {
        if (!$user->getPlainPassword()) {
            return;
        }
        $this->encodePassword($user);

        $em = $args->getEntityManager();
        $em->getUnitOfWork()->persist($user);
        $em->getUnitOfWork()->commit($user);
    }
}

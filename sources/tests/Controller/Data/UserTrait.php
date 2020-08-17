<?php

namespace App\Tests\Controller\Data;

use App\DBAL\Types\UserRole;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Faker\Generator;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Trait UserTrait
 *
 * @property Generator faker
 * @property EntityManager em
 *
 * @method object getService(string $id)
 *
 * @package App\Tests\Controller\Data
 */
trait UserTrait
{
    /**
     * @param array $data
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function makeUser($data = []): User
    {
        $d = new DateTime();
        $user = new User();
        $propertyAccessor = new PropertyAccessor();
        $user
            ->setEmail($this->faker->email)
            ->setFirstName($this->faker->firstName)
            ->setLastName($this->faker->lastName)
            ->setRoles([UserRole::ROLE_USER])
            ->setPlainPassword($this->faker->password)
            ->setCreatedAt($d)
            ->setUpdatedAt($d);

        foreach ($data as $key => $value) {
            $propertyAccessor->setValue($user, $key, $value);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}

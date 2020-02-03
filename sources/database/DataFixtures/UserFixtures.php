<?php

namespace Database\DataFixtures;

use App\DBAL\Types\UserRoleType;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package Database\DataFixtures
 */
class UserFixtures extends Fixture
{
    use FakerAwareTrait;

    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const USER_REFERENCE = 'user';

    public const  ADMIN_USER_EMAIL = 'admin@fixtures.com';
    public const  USER_EMAIL = 'user@fixtures.com';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->makeAdmin();
        $manager->persist($admin);

        $user = $this->makeUser();
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * Create user with role admin
     *
     * @return User
     * @throws \Exception
     */
    private function makeAdmin(): User
    {
        $d = new DateTime();
        $user = new User();
        $user
            ->setRoles([UserRoleType::ROLE_ADMIN])
            ->setFirstName($this->getFaker()->firstName)
            ->setLastName($this->getFaker()->lastName)
            ->setEmail(self::ADMIN_USER_EMAIL)
            ->setCreatedAt($d)
            ->setUpdatedAt($d)
            ->setPassword($this->passwordEncoder->encodePassword($user, self::ADMIN_USER_EMAIL));

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);

        return $user;
    }

    /**
     * Create user with role user
     */
    private function makeUser(): User
    {
        $d = new DateTime();
        $user = new User();
        $user
            ->setRoles([UserRoleType::ROLE_USER])
            ->setFirstName($this->getFaker()->firstName)
            ->setLastName($this->getFaker()->lastName)
            ->setEmail(self::USER_EMAIL)
            ->setCreatedAt($d)
            ->setUpdatedAt($d)
            ->setPassword($this->passwordEncoder->encodePassword($user, self::USER_EMAIL));

        $this->addReference(self::USER_REFERENCE, $user);

        return $user;
    }
}

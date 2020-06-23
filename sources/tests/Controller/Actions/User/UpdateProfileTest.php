<?php

namespace App\Tests\Actions\User;

use App\Entity\User;
use App\Tests\WebTestCaseAbstract;
use Database\DataFixtures\UserFixtures;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class UpdateProfileTest
 *
 * @package App\Tests\Actions\User
 */
class UpdateProfileTest extends WebTestCaseAbstract
{
    use ArraySubsetAsserts;

    /**
     * Test api login
     */
    public function testInvoke()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        /** @var KernelBrowser $client */
        $client = $this->doRequest($user, 'PUT', sprintf('/api/users/profile'), [
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);

        $response = json_decode($client->getResponse()->getContent(), true);

        self::assertArraySubset([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName' => $firstName,
            'lastName' => $lastName,
        ], $response);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

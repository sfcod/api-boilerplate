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
class GetProfileTest extends WebTestCaseAbstract
{
    use ArraySubsetAsserts;

    /**
     * Test api login
     */
    public function testInvoke()
    {
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);

        /** @var KernelBrowser $client */
        $client = $this->doRequest($user, 'GET', sprintf('/api/users/profile'));

        $response = json_decode($client->getResponse()->getContent(), true);

        self::assertArraySubset([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ], $response);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

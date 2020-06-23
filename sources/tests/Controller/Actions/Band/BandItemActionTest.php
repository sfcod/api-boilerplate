<?php

namespace App\Tests\Actions\Band;

use App\Entity\User;
use App\Tests\WebTestCaseAbstract;
use Database\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class UserControllerTest
 *
 * @package App\Tests\Controller\Api
 */
class BandItemActionTest extends WebTestCaseAbstract
{
    /**
     * Test api login
     */
    public function testInvoke()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);
        /** @var KernelBrowser $client */
        $client = $this->doRequest($user, 'GET', sprintf('/api/band'), [
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(count($data) === count([]));
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

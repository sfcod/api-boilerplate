<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\WebTestCaseAbstract;
use Database\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 *
 * @package App\Tests\Controller\Api
 */
class SecurityControllerTest extends WebTestCaseAbstract
{
    /**
     * Test api login
     */
    public function testLogin()
    {
        $client = $this->getKernelClient();
        $client->request(
            'POST',
            '/api/login-check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'username' => 'user@fixtures.com',
                'password' => 'user@fixtures.com',
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(isset($data['token']));
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test forgot password
     */
    public function testForgotPassword()
    {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $client->request('POST', '/api/forgot-password', [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => UserFixtures::USER_EMAIL,
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals([
            'message' => 'A recovery code was sent to your email',
        ], $data);
    }

    /**
     * Test validate token
     *
     * @depends testForgotPassword
     */
    public function testValidateToken()
    {
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);

        /** @var KernelBrowser $client */
        $client = static::createClient();
        $client->request('POST', sprintf('/api/forgot-password/validate-token'), [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'token' => $user->getRecoveryPasswordToken(),
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($data['token']));
    }

    /**
     * Test recovery password
     *
     * @depends testValidateToken
     */
    public function testRecoveryPassword()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);
        /** @var KernelBrowser $client */
        $client = $this->doRequest($user, 'POST', sprintf('/api/users/reset-password'), [
            'password' => UserFixtures::USER_EMAIL,
        ]);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}

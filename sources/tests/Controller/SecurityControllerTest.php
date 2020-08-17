<?php

namespace App\Tests\Controller;

use App\Tests\Controller\Data\UserTrait;
use App\Tests\WebTestCaseAbstract;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserControllerTest
 *
 * @package App\Tests\Controller\Api
 */
class SecurityControllerTest extends WebTestCaseAbstract
{
    use UserTrait;

    /**
     * Test api login
     */
    public function testLogin()
    {
        $user = $this->makeUser();

        $client = $this->getKernelClient();
        $client->request('POST', '/api/login-check', [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => $user->getEmail(),
            'password' => $user->getPlainPassword(),
        ]));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(isset($response['token']));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test forgot password
     */
    public function testForgotPassword()
    {
        $user = $this->makeUser();

        $client = $this->getKernelClient();
        $client->request('POST', '/api/forgot-password', [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => $user->getEmail(),
        ]));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals([
            'message' => 'A recovery code was sent to your email',
        ], $response);
    }

    /**
     * Test validate token
     *
     * @depends testForgotPassword
     */
    public function testValidateToken()
    {
        $user = $this->makeUser([
            'recoveryPasswordToken' => $this->faker->word,
        ]);

        /** @var KernelBrowser $client */
        $client = $this->getKernelClient();
        $client->request('POST', sprintf('/api/forgot-password/validate-token'), [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'token' => $user->getRecoveryPasswordToken(),
        ]));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['token']));
    }

    /**
     * Test recovery password
     *
     * @depends testValidateToken
     */
    public function testRecoveryPassword()
    {
        $user = $this->makeUser([
            'recoveryPasswordToken' => $this->faker->word,
        ]);

        /** @var KernelBrowser $client */
        $client = $this->doRequest($user, 'POST', sprintf('/api/users/reset-password'), [
            'password' => $user->getEmail(),
        ]);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}

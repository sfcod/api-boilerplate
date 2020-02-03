<?php

namespace App\Tests;

use App\Entity\User;
use Common\Entity\Client;
use Database\DataFixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Trait AuthenticatedClient
 *
 * @package App\Tests\Controller
 *
 * @internal
 */
trait AuthenticatedClient
{
//    /**
//     * Create a client with a default Authorization header.
//     *
//     * @param User $user
//     *
//     * @return \Symfony\Bundle\FrameworkBundle\Client
//     */
//    protected function doRequest(string $method, string $endpoint, array $data = [], ?User $user = null)
//    {
//        if (null === $user) {
//            /** @var User $user */
//            $user = $this->em->getRepository(User::class)->findOneBy(['email' => UserFixtures::USER_EMAIL]);
//        }
//
//        /** @var KernelBrowser $client */
//        $client = $this->createAuthenticatedClient($user);
//        $client->request($method, $endpoint, [], [], [
//            'HTTP_ACCEPT' => 'application/json',
//            'CONTENT_TYPE' => 'application/json',
//        ], json_encode($data));
//
//        return $client;
//    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param User $user
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient(User $user = null)
    {
        /** @var $jwtManager JWTTokenManagerInterface */
        $jwtManager = self::$container->get(JWTTokenManagerInterface::class);

        if (!$user) {
            /** @var $em EntityManagerInterface */
            $em = self::$container->get(EntityManagerInterface::class);
            $user = $em->getRepository(User::class)->findOneBy(['email' => 'email-00@zfort.com']);
        }

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $jwtManager->create($user)));

        return $client;
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function doRequest(User $user, string $method, string $endpoint, array $data = [])
    {
        $client = $this->createAuthenticatedClient($user);
        $client->request($method, $endpoint, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $client;
    }
}

<?php

namespace App\Tests;

use App\Entity\User;
use Common\Entity\Client;
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
trait KernelClient
{
    /**
     * @var KernelBrowser
     */
    private $client;

    protected function isTransactionOn(): bool
    {
        return false;
    }

    /**
     * @return KernelBrowser
     */
    protected function getKernelClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return clone $this->client;
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function doRequest(User $user, string $method, string $endpoint, array $data = [])
    {
        $client = $this->getAuthenticatedKernelClient($user);
        $client->request($method, $endpoint, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $client;
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param User $user
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAuthenticatedKernelClient(User $user = null)
    {
        /** @var $jwtManager JWTTokenManagerInterface */
        $jwtManager = self::$container->get(JWTTokenManagerInterface::class);

        if (!$user) {
            /** @var $em EntityManagerInterface */
            $em = self::$container->get(EntityManagerInterface::class);
            $user = $em->getRepository(User::class)->findOneBy(['email' => 'email-00@zfort.com']);
        }

        $client = $this->getKernelClient();
        $client
            ->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $jwtManager->create($user)));

        return $client;
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function doFormRequest(User $user, string $method, string $endpoint, array $data = [], array $files = [], array $headers = [])
    {
        $client = $this->getAuthenticatedKernelClient($user);
        $client->request($method, $endpoint, $data, $files, $headers);

        return $client;
    }
}

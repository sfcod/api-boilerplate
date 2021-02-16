<?php

namespace App\Tests;

use App\Entity\User;
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
     * Create a client with a default Authorization header.
     *
     * @param User|null $user
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function doRequest(User $user, string $method, string $endpoint, array $data = [], array $files = [], array $server = [])
    {
        /** @var $jwtManager JWTTokenManagerInterface */
        $jwtManager = self::$container->get(JWTTokenManagerInterface::class);

        $client = $this->getKernelClient();
        $client
            ->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $jwtManager->create($user)));

        $client->request($method, $endpoint, $files, $server, [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $client;
    }

    /**
     * @return KernelBrowser
     */
    protected function getKernelClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient([
                'environment' => 'test',
            ]);
        }

        return $this->client;
    }
}

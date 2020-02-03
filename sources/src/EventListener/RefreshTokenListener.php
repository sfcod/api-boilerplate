<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProfileUpdateListener
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventListener
 */
final class RefreshTokenListener
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * HeaderUpdateProfileListener constructor.
     */
    public function __construct(JWTTokenManagerInterface $tokenManager, TokenStorageInterface $tokenStorage, Security $security)
    {
        $this->tokenManager = $tokenManager;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
    }

    public function addRefreshTokenHeader(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $statusCode = $response->getStatusCode();
        $token = $this->tokenStorage->getToken(); //$request->attributes->get('data');
        $route = $request->attributes->get('_route');

        if ($token && ($user = $token->getUser()) && $user instanceof User &&
            $statusCode >= 200 && $statusCode <= 300
        ) {
            $response->headers->add([
                'X-Refresh-Token' => $this->tokenManager->create($user),
            ]);
        }
    }
}

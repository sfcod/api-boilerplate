<?php

namespace App\EntityExtensions;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class UserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $authorizationChecker;
    private $request;
    private $entityManager;

    public function __construct(Security $security, AuthorizationCheckerInterface $checker, RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->authorizationChecker = $checker;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (false === $this->supports($resourceClass)) {
            return;
        }

        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * @param string $resourceClass
     */
    private function supports($resourceClass): bool
    {
        return User::class === $resourceClass &&
            false !== strpos($this->request->getPathInfo(), '/api') &&
            false === strpos($this->request->getPathInfo(), '/api/users');
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        // Here can be added some condition
    }

    /**
     * {@inheritdoc}
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (false === $this->supports($resourceClass)) {
            return;
        }

        $this->addWhere($queryBuilder, $resourceClass);
    }
}

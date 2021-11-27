<?php

namespace App\Filters;

use ApiPlatform\Core\Api\FilterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ContextAwareFilterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SelfFilter implements ContextAwareFilterInterface
{
    public function __construct(
        private Security       $security,
    )
    {
    }

    public function apply(
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        string                      $operationName = null,
        array                       $context = []
    )
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException();
        }


        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("$rootAlias.user = :user")->setParameter('user', $user->getId());
    }

    public function getDescription(string $resourceClass): array
    {
        return [];
    }
}
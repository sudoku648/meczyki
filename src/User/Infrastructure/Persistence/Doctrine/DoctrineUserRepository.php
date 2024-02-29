<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Frontend\DataTable\Factory\DataTableUserCriteriaFactory;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView\UserPageView;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;
use function sprintf;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserLoaderInterface, PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function persist(User $user): void
    {
        try {
            $this->_em->beginTransaction();

            $this->_em->persist($user);
            $this->_em->flush();

            $this->_em->commit();
        } catch (Exception $e) {
            $this->_em->rollback();
            $this->registry->resetManager();

            throw $e;
        }
    }

    public function remove(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    public function loadUserByUsername(string $username): ?UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newEncodedPassword
    ): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    get_class($user)
                )
            );
        }

        $user->setPassword($newEncodedPassword);
        $this->persist($user);
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('user')
            ->select('COUNT(user)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(UserPageView $criteria): int
    {
        $qb = $this->getUserQueryBuilder($criteria);

        $qb->select('COUNT(user)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(UserPageView $criteria): array
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getUserQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? DataTableUserCriteriaFactory::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return (array) $pagerfanta->getCurrentPageResults();
    }

    private function getUserQueryBuilder(UserPageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('user');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, UserPageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
                'user.username LIKE :search'
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ('' !== $criteria->usernameLike) {
            $qb->andWhere(
                'user.username LIKE :username'
            )->setParameter('username', '%' . $criteria->usernameLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case DataTableUserCriteriaFactory::SORT_USERNAME:
                $sortColumn = 'user.username';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(User ...$user): void
    {
        $this->createQueryBuilder('user')
            ->where('user IN (?1)')
            ->setParameter(1, $user)
            ->getQuery()
            ->getResult();
    }
}

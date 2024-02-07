<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

use function ucfirst;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrinePersonRepository extends ServiceEntityRepository implements PersonRepositoryInterface
{
    public const SORT_FULLNAME = 'fullName';
    public const SORT_DIR_ASC  = 'ASC';
    public const SORT_DIR_DESC = 'DESC';

    public const SORT_DEFAULT     = self::SORT_FULLNAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function allOrderedByName(?string $type = null): array
    {
        switch ($type) {
            case null:
                break;
            case 'delegate':
            case 'referee':
            case 'refereeObserver':
                $property = 'is' . ucfirst($type);

                break;
            default: throw new LogicException();
        }

        $criteria = [];

        if (isset($property)) {
            $criteria[$property] = true;
        }

        return $this->findBy(
            $criteria,
            [
                'lastName'  => 'ASC',
                'firstName' => 'ASC',
            ]
        );
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('person')
            ->select('COUNT(person)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(PersonPageView|Criteria $criteria): int
    {
        $qb = $this->getPersonQueryBuilder($criteria);

        $qb->select('COUNT(person)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(PersonPageView|Criteria $criteria): PagerfantaInterface
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getPersonQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? self::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return $pagerfanta;
    }

    private function getPersonQueryBuilder(PersonPageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('person');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, PersonPageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
                'CONCAT(person.lastName, \' \', person.firstName) LIKE :search' .
                ' OR ' .
                'CONCAT(person.firstName, \' \', person.lastName) LIKE :search'
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ($criteria->isDelegate) {
            $qb->andWhere('person.isDelegate = :isDelegate')
                ->setParameter('isDelegate', $criteria->isDelegate);
        }
        if ($criteria->isReferee) {
            $qb->andWhere('person.isReferee = :isReferee')
                ->setParameter('isReferee', $criteria->isReferee);
        }
        if ($criteria->isRefereeObserver) {
            $qb->andWhere('person.isRefereeObserver = :isRefereeObserver')
                ->setParameter('isRefereeObserver', $criteria->isRefereeObserver);
        }
        if ($criteria->fullNameLike) {
            $qb->andWhere(
                'CONCAT(person.lastName, \' \', person.firstName) LIKE :fullName' .
                ' OR ' .
                'CONCAT(person.firstName, \' \', person.lastName) LIKE :fullName'
            )->setParameter('fullName', '%' . $criteria->fullNameLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case self::SORT_FULLNAME:
                $sortColumn = 'CONCAT(person.lastName, \' \', person.firstName)';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(Person ...$person): void
    {
        $this->createQueryBuilder('person')
            ->where('person IN (?1)')
            ->setParameter(1, $person)
            ->getQuery()
            ->getResult();
    }
}

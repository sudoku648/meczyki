<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function ucfirst;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    private const PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getRequiredDTData(
        string $start,
        string $length,
        array $orders,
        array $search,
        array $columns,
        ?string $otherConditions = null
    ): array {
        $query = $this->createQueryBuilder('person');
        $query->addSelect('CONCAT(person.lastName, \' \', person.firstName) AS HIDDEN fullName');
        $query->addSelect('CONCAT(person.firstName, \' \', person.lastName) AS HIDDEN fullNameInversed');

        $countQuery = $this->createQueryBuilder('person');
        $countQuery->select('COUNT(person)');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere(
                'CONCAT(person.lastName, \' \', person.firstName) LIKE :search' .
                ' OR ' .
                'CONCAT(person.firstName, \' \', person.lastName) LIKE :search'
            );
            $query->setParameter('search', '%' . $search['value'] . '%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem  = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'fullName':
                        {
                            $searchQuery =
                                'CONCAT(person.lastName, \' \', person.firstName) LIKE :item_' . $colKey .
                                ' OR ' .
                                'CONCAT(person.firstName, \' \', person.lastName) LIKE :item_' . $colKey
                            ;

                            break;
                        }
                }

                if ($searchQuery !== null) {
                    $query->andWhere($searchQuery);
                    $query->setParameter('item_' . $colKey, '%' . $searchItem . '%');
                    $countQuery->andWhere($searchQuery);
                    $countQuery->setParameter('item_' . $colKey, '%' . $searchItem . '%');
                }
            }
        }

        if ($length < 0) {
            $length = null;
        }

        $query->setFirstResult($start)->setMaxResults($length);

        foreach ($orders as $key => $order) {
            if ($order['name'] != '') {
                $orderColumn = null;

                switch ($order['name']) {
                    case 'fullName':
                        {
                            $orderColumn = 'CONCAT(person.lastName, \' \', person.firstName)';

                            break;
                        }
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('fullName', 'ASC');

        $results     = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
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

    public function findByCriteria(Criteria $criteria): PagerfantaInterface
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
            throw new NotFoundHttpException();
        }

        $this->hydrate(...$pagerfanta);

        return $pagerfanta;
    }

    private function getPersonQueryBuilder(Criteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->addOrderBy('p.lastName', 'ASC')
            ->addOrderBy('p.firstName', 'ASC');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, Criteria $criteria): QueryBuilder
    {
        if ($criteria->isDelegate) {
            $qb->andWhere('p.isDelegate = :isDelegate')
                ->setParameter('isDelegate', $criteria->isDelegate);
        }
        if ($criteria->isReferee) {
            $qb->andWhere('p.isReferee = :isReferee')
                ->setParameter('isReferee', $criteria->isReferee);
        }
        if ($criteria->isRefereeObserver) {
            $qb->andWhere('p.isRefereeObserver = :isRefereeObserver')
                ->setParameter('isRefereeObserver', $criteria->isRefereeObserver);
        }
        if ($criteria->fullNameLike) {
            $qb->andWhere(
                'CONCAT(p.lastName, \' \', p.firstName) LIKE :fullName' .
                ' OR ' .
                'CONCAT(p.firstName, \' \', p.lastName) LIKE :fullName'
            )->setParameter('fullName', '%' . $criteria->fullNameLike . '%');
        }

        return $qb;
    }

    public function hydrate(Person ...$people): void
    {
        $this->_em->createQueryBuilder()
            ->select('p')
            ->from(Person::class, 'p')
            ->where('p IN (?1)')
            ->setParameter(1, $people)
            ->getQuery()
            ->getResult();
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
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
    ): array
    {
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
                'CONCAT(person.lastName, \' \', person.firstName) LIKE :search'.
                ' OR '.
                'CONCAT(person.firstName, \' \', person.lastName) LIKE :search'
            );
            $query->setParameter('search', '%'.$search['value'].'%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'fullName':
                    {
                        $searchQuery =
                            'CONCAT(person.lastName, \' \', person.firstName) LIKE :item_'.$colKey.
                            ' OR '.
                            'CONCAT(person.firstName, \' \', person.lastName) LIKE :item_'.$colKey
                        ;
                        break;
                    }
                }

                if ($searchQuery !== null) {
                    $query->andWhere($searchQuery);
                    $query->setParameter('item_'.$colKey, '%'.$searchItem.'%');
                    $countQuery->andWhere($searchQuery);
                    $countQuery->setParameter('item_'.$colKey, '%'.$searchItem.'%');
                }
            }
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

        $results = $query->getQuery()->getResult();
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
                $property = 'is'.\ucfirst($type);
                break;
            default: throw new \LogicException();
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
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MatchGameBill;
use App\Repository\Contracts\MatchGameBillRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchGameBill|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchGameBill|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchGameBill[]    findAll()
 * @method MatchGameBill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineMatchGameBillRepository extends ServiceEntityRepository implements MatchGameBillRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchGameBill::class);
    }
}

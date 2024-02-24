<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameBillRepositoryInterface;

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

    public function persist(MatchGameBill $matchGameBill): void
    {
        $this->_em->persist($matchGameBill);
        $this->_em->flush();
    }

    public function remove(MatchGameBill $matchGameBill): void
    {
        $this->_em->remove($matchGameBill);
        $this->_em->flush();
    }
}

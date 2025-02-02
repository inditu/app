<?php

namespace App\Repository;

use App\Entity\Substance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * SubstanceRepository.
 *
 * @method Substance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Substance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Substance[]    findAll()
 * @method Substance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubstanceRepository extends ServiceEntityRepository
{
    /**
     * __construct.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Substance::class);
    }
}

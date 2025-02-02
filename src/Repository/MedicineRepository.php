<?php

namespace App\Repository;

use App\Entity\Medicine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use Symfony\Component\HttpFoundation\Request;

/**
 * MedicineRepository.
 *
 * @method Medicine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medicine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medicine[]    findAll()
 * @method Medicine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicineRepository extends ServiceEntityRepository
{
    public const SEARCH_BY_DISEASES = 1;
    public const SEARCH_BY_MEDICINCES = 2;

    /**
     * __construct.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicine::class);
    }

    /**
     * search.
     *
     * @param Request $request
     *
     * @return Query
     */
    public function search(Request $request): ?Query
    {
        $searchBy = $request->get('searchBy');
        $name = $request->get('name');
        $builder = $this->_em->createQueryBuilder()
                        ->select('m')
                        ->addSelect('d')
                        ->from(Medicine::class, 'm')
                        ->leftJoin('m.diseases', 'd')
        ;

        if ($name) {
            $builder
                ->andWhere('m.name LIKE :string')
                ->orWhere('d.name LIKE :string')

                ->setParameter('string', '%'.$name.'%', PDO::PARAM_STR);

            return $builder
                ->getQuery();
        }

        return null;
    }
}

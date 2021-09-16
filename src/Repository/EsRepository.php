<?php

namespace App\Repository;

use App\Entity\Es;
use App\Tools\RepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Es|null find($id, $lockMode = null, $lockVersion = null)
 * @method Es|null findOneBy(array $criteria, array $orderBy = null)
 * @method Es[]    findAll()
 * @method Es[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EsRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Es::class);
    }

    // /**
    //  * @return Es[] Returns an array of Es objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Es
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mission[]    findAll()
 * @method Mission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    public function findByEnabled(bool $enabled = true,int $max = null)
    {
        $qb = $this->createQueryBuilder('mission');
        $qb
            ->andWhere('mission.enabled = :enabled')
            ->setParameter('enabled',$enabled)
        ;

        if(!empty($max)){
            $qb->setMaxResults($max);
        }

        return $qb;
    }
}

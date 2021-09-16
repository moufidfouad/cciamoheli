<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Tools\RepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agent[]    findAll()
 * @method Agent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    public function findByPublished(bool $published = true,bool $enabled = null)
    {
        $qb = $this->withFonctionCourante($this->createQueryBuilder('agent'));
        $qb
            ->where('agent.published = :published')
            ->andWhere('fonctionCourante IS NOT NULL')
            ->setParameter('published',$published)
        ;

        if(is_bool($enabled)){
            $qb
                ->andWhere('agent.enabled = :enabled')
                ->setParameter('enabled',$enabled)
            ;            
        }
        
        return $qb;
    }

    public function findByEnabled(bool $enabled = true)
    {
        $qb = $this->withFonctionCourante($this->createQueryBuilder('agent'));
        $qb
            ->where('agent.enabled = :enabled')
            ->setParameter('enabled',$enabled)
        ;
        
        return $qb;
    }

    private function withFonctionCourante(QueryBuilder $qb)
    {
        return $this->findWith($qb,[
            sprintf('%s.fonctionCourante',$qb->getRootAliases()[0]) => 'fonctionCourante'
        ]);        
    }
}

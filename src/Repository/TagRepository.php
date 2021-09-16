<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Tools\Constants;
use App\Tools\RepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findByHasArticle()
    {
        $qb = $this->createQueryBuilder('tag');

        $qb = $this->findWith($qb,[
            'tag.annonces' => 'annonces'
        ])
            ->where('annonces.forme = :forme')
            ->setParameter('forme',Constants::ANNONCE_ARTICLE)
        ;
        
        return $qb;
    }
}

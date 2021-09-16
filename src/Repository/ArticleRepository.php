<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Search\ArticleSearch;
use App\Tools\RepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findBySlug(string $slug,array $joins = [])
    {
        $qb = $this->createQueryBuilder('article');

        $qb
            ->andWhere('article.slug = :slug')
            ->setParameter('slug',$slug)
        ;

        return $this->findWith($qb,$joins);
    }
    
    public function findList(array $joins = [],int $max = null)
    {
        $qb = $this->createQueryBuilder('article');

        if(!empty($max)){
            $qb->setMaxResults($max);
        }

        return $this->findWith($qb,$joins);
    }
    
    public function findBySearch(ArticleSearch $search)
    {
        $qb = $this->createQueryBuilder('article');

        $qb = $this->findWith($qb,[
            'article.tags' => 'tags',
            'article.documents' => 'documents',
            'article.commentaires' => 'commentaires'
        ]);

        if(!empty($search->getQuery())){
            $qb
                ->andWhere('article.titre LIKE :query OR article.sousTitre LIKE :query OR article.contenu LIKE :query')
                ->setParameter('query',"%{$search->getQuery()}%")
            ;   
        }

        if(!$search->getTags()->isEmpty()){                
            $qb
                ->andWhere('tags IN (:tags)')
                ->setParameter('tags',$search->getTags())
            ;               
        }

        return $qb;
    }
}

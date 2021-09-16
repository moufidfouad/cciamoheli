<?php

namespace App\Tools;

use Doctrine\ORM\QueryBuilder;

trait RepositoryTrait
{
    public function findWith(QueryBuilder $qb, array $joins = []): QueryBuilder
    {
        if(!empty($joins)){
            foreach($joins as $k => $v){
                $qb
                    ->leftJoin($k,$v)
                    ->addSelect($v)
                ;
            }
        }
        return $qb;
    }
}
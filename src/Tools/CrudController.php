<?php 

namespace App\Tools;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class CrudController extends AbstractCrudController
{
    protected function getFromRepository(callable $callable,string $entityFqcn = null)
    {
        $entity = $entityFqcn ?? $this->getEntityFqcn();
        $repository = $this->getDoctrine()->getRepository($entity);
        return call_user_func($callable,$repository);
    }    
}
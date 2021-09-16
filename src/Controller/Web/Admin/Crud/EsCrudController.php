<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Es;
use App\Repository\EsRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class EsCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Es::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.es.list.title')  
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->disable(Action::NEW,Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW) 
            ->remove(Crud::PAGE_INDEX, Action::EDIT)  
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)   
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->disable(Action::BATCH_DELETE)        
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            ChoiceField::new('forme','entity.es.fields.forme')
                ->setChoices(
                    Constants::$ENTREE_CHOICES +
                    Constants::$MUTATION_CHOICES +
                    Constants::$SORTIE_CHOICES
                )
        );

        yield(
            AssociationField::new('agent','entity.es.fields.agent')
                
        );

        yield(
            AssociationField::new('destinationInterne','entity.es.fields.destinationInterne')                
        );

        yield(
            DateField::new('dateDebut','entity.es.fields.dateDebut')                
        );
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto,$entityDto,$fields,$filters);
        
        return $this->getFromRepository(function(EsRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.annulation',current($qb->getRootAliases())) => 'annulation',
                sprintf('%s.agent',current($qb->getRootAliases())) => 'agent',
                'agent.fonctionCourante' => 'fonctionCourante',
            ]);
        });
    }
}
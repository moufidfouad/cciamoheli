<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Mutation;
use App\Form\Type\DocumentType;
use App\Repository\FonctionRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class MutationCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Mutation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.mutation.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.mutation.edit.title')
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->disable(Action::INDEX)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)   
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)          
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {

        yield(
            ChoiceField::new('forme','entity.mutation.fields.forme')
                ->setChoices(Constants::$MUTATION_CHOICES)
        );
        
        yield(
            AssociationField::new('agent','entity.mutation.fields.agent')
                ->setFormTypeOption('choice_label','fullname')
                ->setRequired(true)
                
        );

        yield(
            AssociationField::new('destinationInterne','entity.mutation.fields.destinationInterne')
                ->setFormTypeOption('query_builder',function(FonctionRepository $repository){
                    $qb = $repository->createQueryBuilder('fonction');
                    return $repository->findWith($qb,[
                        'fonction.agent' => 'agent'
                    ])
                        ->where('agent IS NULL')
                        ->andWhere('fonction.enabled = :enabled')
                        ->setParameter('enabled',true)
                    ;
                })
                ->setFormTypeOption('choice_label','titre')
                ->setRequired(true)
                ->hideOnIndex()
        );

        yield(
            DateField::new('dateDebut','entity.mutation.fields.dateDebut')
        );

        yield(
            DateField::new('dateFin','entity.mutation.fields.dateFin')
                ->hideOnIndex()
        );

        yield(
            CollectionField::new('documents','entity.entree.fields.documents')
                ->setEntryType(DocumentType::class)
                ->hideOnIndex()
        );

        yield(
            DateField::new('createdAt','createdAt')
                ->hideOnForm()
                ->hideOnIndex()
        );

        yield(
            DateField::new('updatedAt','updatedAt')
                ->hideOnForm()
                ->hideOnIndex()
        );
    }
}
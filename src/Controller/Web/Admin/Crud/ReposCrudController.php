<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Repos;
use App\Repository\AgentRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ReposCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Repos::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.repos.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.repos.edit.title')
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
            ChoiceField::new('forme','entity.es.fields.forme')
                ->setChoices(Constants::$REPOS_CHOICES)
        );

        yield(
            AssociationField::new('agent','entity.es.fields.agent')
                ->setFormTypeOption('query_builder',function(AgentRepository $repository){
                    return $repository->findByEnabled();
                })
                ->setRequired(true)
        );

        yield(
            DateField::new('dateDebut','entity.es.fields.dateDebut')
        );

        yield(
            DateField::new('dateFin','entity.es.fields.dateFin')
        );

        yield(
            CollectionField::new('documents','entity.es.fields.documents')
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
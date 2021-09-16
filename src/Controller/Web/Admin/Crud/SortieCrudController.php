<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Sortie;
use App\Form\Type\DocumentType;
use App\Tools\Constants;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class SortieCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.sortie.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.sortie.edit.title')
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
            ChoiceField::new('forme','entity.sortie.fields.forme')
                ->setChoices(Constants::$SORTIE_CHOICES)
        );

        yield(
            AssociationField::new('agent','entity.sortie.fields.agent')
                ->setRequired(true)
                ->onlyWhenCreating()
        );

        yield(
            DateField::new('dateDebut','entity.sortie.fields.dateDebut')
        );

        yield(
            DateField::new('dateFin','entity.sortie.fields.dateFin')
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
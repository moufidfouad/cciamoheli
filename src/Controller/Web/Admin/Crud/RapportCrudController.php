<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Rapport;
use App\Form\Type\DocumentType;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RapportCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Rapport::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.rapport.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.rapport.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.rapport.edit.title')
            ->addFormTheme('themes/_rapport_document_form_theme.html.twig')
        ;

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)            
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            TextField::new('titre','entity.rapport.fields.titre')
        );

        yield(
            AssociationField::new('agent','entity.rapport.fields.agent')
                ->onlyOnDetail()
        );

        yield(
            CollectionField::new('documents','entity.rapport.fields.documents')
                ->setEntryType(DocumentType::class)
                ->setTemplatePath('admin/fields/documents.html.twig')
        );
    }
}
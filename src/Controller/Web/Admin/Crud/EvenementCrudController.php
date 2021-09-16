<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Evenement;
use App\Form\Field\VichField;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EvenementCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Evenement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.evenement.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.evenement.edit.title')
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)   
            ->disable(Action::BATCH_DELETE)         
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            TextField::new('titre','entity.evenement.fields.titre')
        );
        
        yield(
            DateTimeField::new('debut','entity.evenement.fields.debut')
        );
        
        yield(
            DateTimeField::new('fin','entity.evenement.fields.fin')
        );

        yield(
            TextEditorField::new('description','entity.evenement.fields.description')
                ->hideOnIndex()
        );

        yield(
            VichField::new('file','entity.evenement.fields.file')
                ->setFormTypeOption('required',$pageName == Crud::PAGE_NEW)
                ->setFormTypeOptionIfNotSet('attr',[
                    'accept' => 'image/*'
                ])
                ->hideOnIndex()
        );

        yield(
            ImageField::new('image','entity.evenement.fields.file')
                ->setBasePath($this->getParameter('vich.path.evenement_images'))
                ->hideOnForm()
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
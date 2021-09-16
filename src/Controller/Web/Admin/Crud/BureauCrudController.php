<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Bureau;
use App\Repository\BureauRepository;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BureauCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Bureau::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.bureau.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.bureau.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.bureau.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.bureau.show.title')
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)   
            ->remove(Crud::PAGE_DETAIL, Action::INDEX)   
            ->disable(Action::BATCH_DELETE)         
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            TextField::new('titre','entity.bureau.fields.titre')
        );

        yield(
            AssociationField::new('parent','entity.bureau.fields.parent')
                ->setFormTypeOption('choice_label','titre')
                ->setFormTypeOption('query_builder',function(BureauRepository $repository){
                    $qb = $repository->createQueryBuilder('bureau');
                    $qb
                        ->where('bureau.enabled = :enabled')
                        ->setParameter('enabled',true)
                    ;
                    return $qb;
                })
        );

        yield(
            BooleanField::new('enabled','entity.bureau.fields.enabled')
        );

        yield(
            TextEditorField::new('description','entity.bureau.fields.description')
        );

        yield(
            AssociationField::new('fonctions','entity.bureau.fields.fonctions')
                ->setTemplatePath('admin/bureau/fields/fonctions.html.twig')
                ->onlyOnDetail()
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
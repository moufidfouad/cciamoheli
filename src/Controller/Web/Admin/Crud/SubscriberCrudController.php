<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\NewsLetter\Subscriber;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class SubscriberCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Subscriber::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.subscriber.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.subscriber.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.subscriber.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.subscriber.show.title')
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
            ->disable(Action::DETAIL,Action::BATCH_DELETE)           
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            EmailField::new('email','entity.subscriber.fields.email')
        );

        yield(
            BooleanField::new('enabled','entity.subscriber.fields.enabled')
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

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add(
                TextFilter::new('email','entity.subscriber.filters.email')
            )
        ;

        return $filters;
    }
}
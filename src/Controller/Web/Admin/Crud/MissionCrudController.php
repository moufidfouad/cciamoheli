<?php

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Mission;
use App\Form\Field\VichField;
use App\Tools\CrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.mission.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.mission.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.mission.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.mission.show.title')
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
            TextField::new('description','entity.mission.fields.description')
        );

        yield(
            BooleanField::new('enabled','entity.mission.fields.enabled')
        );

        yield(
            VichField::new('file','entity.mission.fields.file')
                ->setFormTypeOption('required',$pageName == Crud::PAGE_NEW)
                ->setFormTypeOptionIfNotSet('attr',[
                    'accept' => 'image/*'
                ])
                ->onlyOnForms()
        );

        yield(
            ImageField::new('image','entity.mission.fields.file')
                ->setBasePath($this->getParameter('vich.path.mission_images'))
                ->hideOnForm()
                ->hideOnIndex()
        );
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //dd($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
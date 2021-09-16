<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Fonction;
use App\Repository\FonctionRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FonctionCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Fonction::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.fonction.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.fonction.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.fonction.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.fonction.show.title')
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
            TextField::new('titre','entity.fonction.fields.titre')
        );

        yield(
            BooleanField::new('enabled','entity.fonction.fields.enabled')
                ->hideOnIndex()
        );

        yield(
            TextEditorField::new('description','entity.fonction.fields.description')
        );

        yield(
            AssociationField::new('bureau','entity.fonction.fields.bureau')
                ->setFormTypeOption('choice_label','titre')
        );

        yield(
            AssociationField::new('agent','entity.fonction.fields.agent')
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

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto,$entityDto,$fields,$filters);
        
        return $this->getFromRepository(function(FonctionRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.bureau',current($qb->getRootAliases())) => 'bureau'
            ]);
        });
    }
}
<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Publication;
use App\Form\Field\TagField;
use App\Form\Type\DocumentType;
use App\Repository\PublicationRepository;
use App\Tools\CrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PublicationCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Publication::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.publication.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.publication.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.publication.edit.title')
            ->addFormTheme('themes/_publication_document_form_theme.html.twig')
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
            TextField::new('titre','entity.publication.fields.titre')
        );

        yield(
            TagField::new('tags','entity.publication.fields.tags')
                ->setTemplatePath('admin/fields/tags.html.twig')
                ->hideOnIndex()
        );

        yield(
            CollectionField::new('documents','entity.publication.fields.documents')
                ->setEntryType(DocumentType::class)
                ->setTemplatePath('admin/fields/documents.html.twig')
        );

        yield(
            TextField::new('auteur','entity.publication.fields.auteur')
                ->setRequired(false)
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
        
        return $this->getFromRepository(function(PublicationRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.documents',current($qb->getRootAliases())) => 'documents'
            ]);
        });
    }
}
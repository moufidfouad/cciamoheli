<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Article;
use App\Form\Field\TagField;
use App\Form\Field\VichField;
use App\Form\Type\DocumentType;
use App\Repository\ArticleRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ArticleCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->showEntityActionsAsDropdown(true)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.article.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.article.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.article.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.article.show.title')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('themes/_article_document_form_theme.html.twig')
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
                TextField::new('titre','entity.article.fields.titre')
            );
    
            yield(
                TextField::new('sousTitre','entity.article.fields.soustitre')
                    ->hideOnIndex()
            );
    
            yield(
                TagField::new('tags','entity.article.fields.tags')
                    ->setTemplatePath('admin/fields/tags.html.twig')
                    ->hideOnIndex()
            );
    
            yield(
                VichField::new('file','entity.article.fields.file')
                    ->setFormTypeOption('required',$pageName == Crud::PAGE_NEW)
                    ->setFormTypeOptionIfNotSet('attr',[
                        'accept' => 'image/*'
                    ])
                    ->onlyOnForms()
            );
    
            yield(
                ImageField::new('image','entity.article.fields.file')
                    ->setBasePath($this->getParameter('vich.path.article_images'))
                    ->hideOnForm()
                    ->hideOnIndex()
            );
    
            
            yield(
                TextEditorField::new('contenu','entity.article.fields.contenu')
                    ->setFormType(CKEditorType::class)
                    ->hideOnIndex()
            );
    
            yield(
                TextField::new('auteur','entity.article.fields.auteur')
                    ->setRequired(false)
            );
    
            yield(
                CollectionField::new('documents','entity.rapport.fields.documents')
                    ->setEntryType(DocumentType::class)
                    ->setTemplatePath('admin/fields/documents.html.twig')
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
        
        return $this->getFromRepository(function(ArticleRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.documents',current($qb->getRootAliases())) => 'documents'
            ]);
        });
    }
}
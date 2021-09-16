<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentaireCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaire::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.commentaire.list.title')
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->disable(Action::NEW,Action::DETAIL,Action::BATCH_DELETE)            
        ;
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield(
            TextField::new('name','entity.commentaire.fields.name.label')
        );

        yield(
            TextField::new('email','entity.commentaire.fields.email.label')
        );

        yield(
            TextareaField::new('message','entity.commentaire.fields.message.label')
        );

        yield(
            AssociationField::new('article','entity.commentaire.fields.article')
        );

        yield(
            BooleanField::new('enabled','entity.commentaire.fields.enabled')
        );

        yield(
            DateField::new('createdAt','createdAt')
        );
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto,$entityDto,$fields,$filters);
        
        return $this->getFromRepository(function(CommentaireRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.article',current($qb->getRootAliases())) => 'article'
            ]);
        });
    }
}
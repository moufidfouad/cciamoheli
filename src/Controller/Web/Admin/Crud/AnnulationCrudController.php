<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Annulation;
use App\Form\Type\DocumentType;
use App\Repository\EsRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class AnnulationCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Annulation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.annulation.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.annulation.edit.title')
            ->addFormTheme('themes/_annulation_document_form_theme.html.twig')
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
            AssociationField::new('es','entity.annulation.fields.es')
                ->setFormTypeOption('query_builder',function(EsRepository $repository){
                    $qb = $repository->findWith($repository->createQueryBuilder('es'),[
                        'es.agent' => 'agent'
                    ]);
                    $qb
                        ->where('es.forme IN (:forme)')
                        ->setParameter('forme',array_values(Constants::$ENTREE_CHOICES) + array_values(Constants::$MUTATION_CHOICES) + array_values(Constants::$SORTIE_CHOICES))
                    ;

                    return $qb;
                })
        );

        yield(
            DateField::new('dateDebut','entity.annulation.fields.dateDebut')
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
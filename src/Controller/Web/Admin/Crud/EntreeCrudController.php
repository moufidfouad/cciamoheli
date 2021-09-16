<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Entree;
use App\Form\Field\Es\AgentField;
use App\Form\Type\DocumentType;
use App\Repository\FonctionRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntreeCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Entree::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_NEW, 'entity.entree.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.entree.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.entree.show.title')
            ->addFormTheme('themes/_entree_document_form_theme.html.twig')
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
            ChoiceField::new('forme','entity.es.fields.forme')
                ->setChoices(Constants::$ENTREE_CHOICES)
        );
        
        yield(
            AgentField::new('agent','entity.es.fields.agent')
        );

        yield(
            TextField::new('origineExterne','entity.es.fields.origineExterne')
        );

        yield(
            AssociationField::new('destinationInterne','entity.es.fields.destinationInterne')
                ->setFormTypeOption('query_builder',function(FonctionRepository $repository){
                    $qb = $repository->createQueryBuilder('fonction');
                    return $repository->findWith($qb,[
                        'fonction.agent' => 'agent'
                    ])
                        ->where('agent IS NULL')
                        ->andWhere('fonction.enabled = :enabled')
                        ->setParameter('enabled',true)
                    ;
                })
                ->setFormTypeOption('choice_label','titre')
                ->setRequired(true)
        );

        yield(
            DateField::new('dateDebut','entity.es.fields.dateDebut')
        );

        yield(
            DateField::new('dateFin','entity.es.fields.dateFin')
        );

        yield(
            CollectionField::new('documents','entity.es.fields.documents')
                ->setEntryType(DocumentType::class)
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
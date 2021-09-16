<?php 

namespace App\Controller\Web\Admin\Crud;

use App\Entity\Agent;
use App\Repository\AgentRepository;
use App\Tools\Constants;
use App\Tools\CrudController;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class AgentCrudController extends CrudController
{
    public static function getEntityFqcn(): string
    {
        return Agent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud
            ->setSearchFields(null)
            ->setPageTitle(Crud::PAGE_INDEX, 'entity.agent.list.title')
            ->setPageTitle(Crud::PAGE_NEW, 'entity.agent.create.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'entity.agent.edit.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'entity.agent.show.title')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->disable(Action::NEW)
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
            TextField::new('username','entity.agent.fields.username')
        );

        yield(
            TextField::new('nom','entity.agent.fields.nom')
        );

        yield(
            TextField::new('prenom','entity.agent.fields.prenom')
        );

        yield(
            ChoiceField::new('genre','entity.agent.fields.genre')
                ->setChoices(Constants::$GENRE_CHOICES)
        );

        yield(
            BooleanField::new('enabled','entity.agent.fields.enabled')
                ->hideOnIndex()
        );

        yield(
            BooleanField::new('published','entity.agent.fields.published')
                ->hideOnIndex()
        );

        yield(
            TextField::new('nin','entity.agent.fields.nin')
                ->hideOnIndex()
        );

        yield(
            DateField::new('ddn','entity.agent.fields.ddn')
                ->hideOnIndex()
        );

        yield(
            TextField::new('ldn','entity.agent.fields.ldn')
                ->hideOnIndex()
        );

        yield(
            TextField::new('telephone','entity.agent.fields.telephone')
        );

        yield(
            ChoiceField::new('roles','entity.agent.fields.roles')
                ->setChoices(Constants::$ROLES_CHOICES)
                ->allowMultipleChoices(true)
                ->setRequired(false)
                ->onlyOnForms()
        );

        yield(
            BooleanField::new('enabled','entity.agent.fields.enabled')
                ->onlyOnForms()
        );

        yield(
            AssociationField::new('es','entity.agent.fields.es')
                ->setTemplatePath('admin/agent/fields/es.html.twig')
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

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add(
                TextFilter::new('username','entity.agent.filters.username')
            )
            ->add(
                TextFilter::new('nom','entity.agent.filters.nom')
            )
            ->add(
                TextFilter::new('prenom','entity.agent.filters.prenom')
            )
            ->add(
                ChoiceFilter::new('genre','entity.agent.filters.genre')
                    ->setChoices(Constants::$GENRE_CHOICES)
                    ->setFormTypeOptions([
                        'translation_domain' => 'messages',
                        'attr' => [
                            'data-widget' => 'select2'
                        ]
                    ])
            )
            ->add(
                TextFilter::new('nin','entity.agent.filters.nin')
            )
            ->add(
                TextFilter::new('telephone','entity.agent.filters.telephone')
            )
            ->add(
                BooleanFilter::new('enabled','entity.agent.filters.enabled')
            )
            ->add(
                BooleanFilter::new('published','entity.agent.filters.published')
            )
        ;
        return $filters;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto,$entityDto,$fields,$filters);
        
        return $this->getFromRepository(function(AgentRepository $repository) use ($qb){
            return $repository->findWith($qb,[
                sprintf('%s.fonctionCourante',current($qb->getRootAliases())) => 'fonctionCourante'
            ]);
        });
    }
}
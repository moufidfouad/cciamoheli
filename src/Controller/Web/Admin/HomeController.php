<?php

namespace App\Controller\Web\Admin;

use App\Entity\Agent;
use App\Entity\Annulation;
use App\Entity\Article;
use App\Entity\Bureau;
use App\Entity\Commentaire;
use App\Entity\Entree;
use App\Entity\Es;
use App\Entity\Evenement;
use App\Entity\Fonction;
use App\Entity\Mission;
use App\Entity\Mutation;
use App\Entity\NewsLetter\Subscriber;
use App\Entity\Publication;
use App\Entity\Rapport;
use App\Entity\Repos;
use App\Entity\Sortie;
use App\Entity\User;
use App\Tools\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends DashboardController
{
    /**
     * @Route("/admin", name="web_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    } 

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new();

        $dashboard
            ->setFaviconPath('build/mirko/assets/images/favicon.png')
            ->setTitle($this->get('translator')->trans('configuration.name.short'))
            ->renderContentMaximized()
            ->disableUrlSignatures()
            ->generateRelativeUrls()
        ;

        return $dashboard;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $userMenu = UserMenu::new();

        $userMenu
            ->setName($user instanceof User ? $user->getFullname() : $user->getUserIdentifier())
            ->addMenuItems([
                //MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                //MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('security.logout.title', 'fa fa-sign-out'),
            ])
        ;

        return $userMenu;
    }
    
    public function configureMenuItems(): iterable
    {
        yield(
            MenuItem::linktoDashboard('menu.admin.homepage', 'fa fa-home')
        );
        yield(
            MenuItem::subMenu('entity.article.name','fa fa-pencil')->setSubItems([
                MenuItem::linkToCrud('entity.article.list.label','fa fa-th-list',Article::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.commentaire.list.label','fa fa-comment',Commentaire::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.article.create.label','fa fa-plus',Article::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_ARTICLE)
        );

        yield(
            MenuItem::subMenu('entity.publication.name','fa fa-file-alt')->setSubItems([
                MenuItem::linkToCrud('entity.publication.list.label','fa fa-th-list',Publication::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.publication.create.label','fa fa-plus',Publication::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_PUBLICATION)
        );

        /*yield(
            MenuItem::linkToRoute('entity.failedMsg.list.label','fa fa-th-list','web_admin_failed_msg')
        );*/

        yield(
            MenuItem::subMenu('entity.subscriber.name','fa fa-group')->setSubItems([
                MenuItem::linkToCrud('entity.subscriber.list.label','fa fa-th-list',Subscriber::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.subscriber.create.label','fa fa-plus',Subscriber::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_AGENT)
        );

        yield(
            MenuItem::subMenu('entity.evenement.name','fa fa-calendar-alt')->setSubItems([
                MenuItem::linkToCrud('entity.evenement.list.label','fa fa-th-list',Evenement::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.evenement.create.label','fa fa-plus',Evenement::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_EVENEMENT)
        );

        yield(
            MenuItem::subMenu('entity.bureau.name','fa fa-institution')->setSubItems([
                MenuItem::linkToCrud('entity.bureau.list.label','fa fa-th-list',Bureau::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.bureau.create.label','fa fa-plus',Bureau::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_BUREAU)
        );

        yield(
            MenuItem::subMenu('entity.fonction.name','fa fa-bookmark')->setSubItems([
                MenuItem::linkToCrud('entity.fonction.list.label','fa fa-th-list',Fonction::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.fonction.create.label','fa fa-plus',Fonction::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_FONCTION)
        );

        yield(
            MenuItem::subMenu('entity.es.name','fa fa-exchange')->setSubItems([
                MenuItem::linkToCrud('entity.es.list.label','fa fa-list',Es::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.entree.create.label','fa fa-plus',Entree::class)
                    ->setQueryParameter('crudAction','new'),
                MenuItem::linkToCrud('entity.mutation.create.label','fa fa-plus',Mutation::class)
                    ->setQueryParameter('crudAction','new'),
                MenuItem::linkToCrud('entity.repos.create.label','fa fa-plus',Repos::class)
                    ->setQueryParameter('crudAction','new'),
                MenuItem::linkToCrud('entity.sortie.create.label','fa fa-plus',Sortie::class)
                    ->setQueryParameter('crudAction','new'),
                MenuItem::linkToCrud('entity.annulation.create.label','fa fa-plus',Annulation::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_ES)
        );

        yield(
            MenuItem::subMenu('entity.mission.name','fa fa-cog')->setSubItems([
                MenuItem::linkToCrud('entity.mission.list.label','fa fa-th-list',Mission::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.mission.create.label','fa fa-plus',Mission::class)
                    ->setQueryParameter('crudAction','new')
            ]) ->setPermission(User::ROLE_ADMIN_MISSION)
        );

        yield(
            MenuItem::linkToCrud('entity.agent.list.label','fa fa-th-list',Agent::class)
                ->setQueryParameter('crudAction','index')
                ->setPermission(User::ROLE_ADMIN_AGENT)
        );

        yield(
            MenuItem::subMenu('entity.rapport.name','fa fa-book')->setSubItems([
                MenuItem::linkToRoute('entity.rapport.mine.label','fa fa-th-list',Rapport::class)
                    ->setQueryParameter('crudAction','index'),
                MenuItem::linkToCrud('entity.rapport.create.label','fa fa-plus',Rapport::class)
                    ->setQueryParameter('crudAction','new')
            ])
        );
    }
}
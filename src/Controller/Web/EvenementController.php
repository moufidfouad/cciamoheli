<?php

namespace App\Controller\Web;

use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("", name="web_evenement_index")
     */
    public function index(Request $request,PaginatorInterface $paginator,EvenementRepository $evenementRepository): Response
    {
        $queryArticle = $evenementRepository->createQueryBuilder('evenement')->orderBy('evenement.createdAt','ASC');
        $pager = $paginator->paginate($queryArticle,$request->query->getInt('page',1),$request->query->getInt('limit',10));
        return $this->render('evenement/index.html.twig',[
            'pager' => $pager
        ]);
    }
}
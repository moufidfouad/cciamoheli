<?php

namespace App\Controller\Web;

use App\Entity\Commentaire;
use App\Entity\Search\ArticleSearch;
use App\Form\Search\ArticleSearchType;
use App\Form\Type\CommentaireType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("", name="web_blog_index")
     */
    public function index(Request $request,ArticleRepository $articleRepository,PaginatorInterface $paginator): Response
    {
        $searchArticle = new ArticleSearch();
        $searchArticleForm = $this->createForm(ArticleSearchType::class,$searchArticle,[
            'method' => Request::METHOD_GET,
            'action' => $this->generateUrl($request->get('_route'))
        ]);
        $searchArticleForm->handleRequest($request);
        $queryArticle = $articleRepository->findBySearch($searchArticle)->orderBy('article.createdAt','ASC');
        $pager = $paginator->paginate($queryArticle,$request->query->getInt('page',$searchArticle->getPage()),$request->query->getInt('limit',$searchArticle->getLimit()));
        return $this->render('blog/index.html.twig',[
            'pager' => $pager
        ]);
    }

    /**
     * @Route("/{slug}", name="web_blog_show")
     */
    public function show(Request $request,ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findBySlug($request->attributes->get('slug'),[
            'article.tags' => 'tags'
        ])->getQuery()->getOneOrNullResult();
        
        $commentaire = new Commentaire();
        $commentaireForm = $this->createForm(CommentaireType::class,$commentaire,[
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('web_commentaire_add',[
                'slug' => $article->getSlug()
            ])
        ]);
        return $this->render('blog/show.html.twig',[
            'article' => $article,
            'form' => $commentaireForm->createView()
        ]);
    }
}
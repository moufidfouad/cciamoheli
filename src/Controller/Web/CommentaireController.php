<?php 

namespace App\Controller\Web;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\Type\CommentaireType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/{slug}/add", name="web_commentaire_add", methods={"POST"})
     */
    public function add(Request $request,Article $article,ArticleRepository $articleRepository): Response
    {
        $commentaire = (new Commentaire())->setArticle($article);
        $form = $this->createForm(CommentaireType::class,$commentaire,[
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('web_commentaire_add',[
                'slug' => $article->getSlug()
            ])
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaire);
                $em->flush();

                return new RedirectResponse($this->generateUrl('web_blog_show',[
                    'slug' => $article->getSlug()
                ]),Response::HTTP_SEE_OTHER);
            }
            $article = $articleRepository->findBySlug($request->attributes->get('slug'))->getQuery()->getOneOrNullResult();
            return new Response($this->renderView('blog/show.html.twig',[
                'article' => $article,
                'form' => $form->createView()
            ]),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
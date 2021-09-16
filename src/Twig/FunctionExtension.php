<?php

namespace App\Twig;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Evenement;
use App\Entity\NewsLetter\Subscriber;
use App\Entity\Search\ArticleSearch;
use App\Form\Search\ArticleSearchType;
use App\Form\Type\ContactType;
use App\Form\Type\NewsletterType;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionExtension extends AbstractExtension
{

    /** @var FormFactoryInterface */
    private $form;
    /** @var Environment */
    private $twig;
    /** @var UrlGeneratorInterface */
    private $router;
    /** @var ArticleRepository */
    private $articleRepository;
    
    public function __construct(
        FormFactoryInterface $form,
        Environment $twig,
        UrlGeneratorInterface $router,
        ArticleRepository $articleRepository
    )
    {
        $this->form = $form;
        $this->twig = $twig;
        $this->router = $router;
        $this->articleRepository = $articleRepository;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('social_icons_view', [$this, 'getSocialIcons'], ['is_safe' => ['html']]),
            new TwigFunction('blog_form_view', [$this, 'getBlogFormView'], ['is_safe' => ['html']]),
            //new TwigFunction('publication_form_view', [$this, 'getPublicationFormView'], ['is_safe' => ['html']]),
            new TwigFunction('newsletter_form_view', [$this, 'getNewsletterFormView'], ['is_safe' => ['html']]),
            new TwigFunction('contact_form_view', [$this, 'getContactFormView'], ['is_safe' => ['html']]),
            new TwigFunction('link_show_more', [$this, 'getLinkShowMore'], ['is_safe' => ['html']]),
            new TwigFunction('display_article', [$this, 'getDisplayArticle'], ['is_safe' => ['html']]),
            new TwigFunction('periode_evenement_view', [$this, 'getPeriodeEvenement'], ['is_safe' => ['html']]),
            new TwigFunction('recent_articles', [$this, 'getRecentArticles']),
        ];
    }

    public function getSocialIcons()
    {
        return $this->twig->render('extensions/social_icons_view.html.twig');        
    }

    public function getBlogFormView(Request $request)
    {
        $searchArticle = new ArticleSearch();
        $form = $this->form->create(ArticleSearchType::class,$searchArticle,[
            'method' => Request::METHOD_GET,
            'action' => $this->router->generate('web_blog_index')
        ]);
        $form->handleRequest($request);
        return $this->twig->render('extensions/blog_form_view.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function getNewsletterFormView(Request $request)
    {
        $subscriber = new Subscriber();
        $form = $this->form->create(NewsletterType::class,$subscriber,[
            'method' => Request::METHOD_POST,
            'action' => $this->router->generate('web_newsletter_subscribe')
        ]);
        $form->handleRequest($request);
        return $this->twig->render('extensions/newsletter_form_view.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function getContactFormView(Request $request)
    {
        $contact = new Contact();
        $form = $this->form->create(ContactType::class,$contact,[
            'method' => Request::METHOD_POST,
            'action' => $this->router->generate('web_contact')
        ]);
        $form->handleRequest($request);
        return $this->twig->render('extensions/contact_form_view.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function getDisplayArticle(Article $article,bool $detail = false)
    {
        return $this->twig->render('extensions/display_article.html.twig',[
            'article' => $article,
            'detail' => $detail
        ]);        
    }

    public function getLinkShowMore(string $route,array $attributes = [],string $label ='')
    {
        return $this->twig->render('extensions/link_show_more.html.twig',[
            'route' => $route,
            'attributes' => $attributes,
            'label' => $label
        ]);        
    }

    public function getPeriodeEvenement(Evenement $evenement)
    {
        return $this->twig->render('extensions/periode_evenement_view.html.twig',[
            'item' => $evenement
        ]);       
    }

    public function getRecentArticles(int $max = 10)
    {
        return $this->articleRepository->findList([],$max)->getQuery()->getResult();
    }
}
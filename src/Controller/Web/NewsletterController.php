<?php

namespace App\Controller\Web;

use App\Entity\NewsLetter\Subscriber;
use App\Form\Type\NewsletterType;
use App\Messenger\NewsletterConfirmationEmail;
use App\Messenger\NewsletterSubscriptionEmail;
use App\Messenger\NewsletterUnsubscriptionEmail;
use App\Tools\FormController;
use App\Tools\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends AbstractController
{
    use FormController;

    /**
     * @Route("/subscribe", name="web_newsletter_subscribe", methods={"POST"}, condition="request.isXmlHttpRequest()")
     */
    public function subscribe(Request $request): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(NewsletterType::class,$subscriber);

        $form->handleRequest($request);

        $response = [
            'form' => $this->renderView('extensions/newsletter_form_view.html.twig',[
                'form' => $form->createView()
            ])
        ];

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $subscriber->setToken(Utils::getToken(25));
                $em->persist($subscriber);
                $em->flush();
                $this->dispatchMessage(
                    new NewsletterSubscriptionEmail(
                        $subscriber->getEmail(),
                        $subscriber->getToken()
                    )
                );
                $status = Response::HTTP_CREATED;
            }else{
                $response = $response + [
                    'error' => $this->getErrorsFromForm($form)
                ];
                $status = Response::HTTP_NON_AUTHORITATIVE_INFORMATION;
            }             
        }
        return new JsonResponse($response,$status);
    }

    /**
     * @Route("/confirm/{token}", name="web_newsletter_confirm")
     */
    public function confirm(Request $request): Response
    {
        $subscriber = $this->getDoctrine()->getRepository(Subscriber::class)->findOneBy([
            'token' => $request->attributes->get('token')
        ]);
        
        if(empty($subscriber)){
            return $this->redirectToRoute('web_index');
        }
        $subscriber->setEnabled(true)->setToken(null);
        $this->dispatchMessage(
            new NewsletterConfirmationEmail(
                $subscriber->getEmail(),
                $subscriber->getId()
            )
        );

        $this->getDoctrine()->getManager()->flush();
        
        return $this->render('newsletter/confirmation.html.twig',[
            'id' => $subscriber->getId()
        ]);
    }

    /**
     * @Route("/unsubscribe/{id}", name="web_newsletter_unsubscribe")
     */
    public function unsubscribe(Request $request): Response
    {
        $subscriber = $this->getDoctrine()->getRepository(Subscriber::class)->findOneBy([
            'id' => $request->attributes->get('id')
        ]);
        if(empty($subscriber)){
            return $this->redirectToRoute('web_index');
        }
        $this->getDoctrine()->getManager()->remove($subscriber);
        $this->getDoctrine()->getManager()->flush();

        $this->dispatchMessage(
            new NewsletterUnsubscriptionEmail(
                $subscriber->getEmail()
            )
        );
        
        return $this->render('newsletter/unsubscription.html.twig');
    }
}
<?php

namespace App\Controller\Web;

use App\Entity\Contact;
use App\Form\Type\ContactType;
use App\Messenger\ContactSubmissionEmail;
use App\Tools\FormController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    use FormController;
    /**
     * @Route("", name="web_contact", methods={"POST"}, condition="request.isXmlHttpRequest()")
     */
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);

        $response = [
            'form' => $this->renderView('extensions/contact_form_view.html.twig',[
                'form' => $form->createView()
            ])
        ];

        if($form->isSubmitted()){
            if($form->isValid()){
                $this->dispatchMessage(
                    new ContactSubmissionEmail(
                        $contact->getEmail(),
                        $this->getParameter('contact_email'),
                        $contact->getPhone(),
                        $contact->getMessage()
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
}
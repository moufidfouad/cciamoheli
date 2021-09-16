<?php

namespace App\Messenger;

use App\Gateway\MailerGatewayInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EmailHandler implements MessageHandlerInterface
{
    /** @var MailerGatewayInterface */
    private $mailerGateway;
    /** @var ParameterBagInterface */
    private $bag;
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        MailerGatewayInterface $mailerGateway,
        ParameterBagInterface $bag,
        TranslatorInterface $translator
    )
    {
        $this->mailerGateway = $mailerGateway;
        $this->bag = $bag;
        $this->translator = $translator;
    }

    public function __invoke(Email $email)
    {
        switch (get_class($email)) {
            case ContactSubmissionEmail::class:
                $this->submitContact($email);
                break;

            case NewsletterSubscriptionEmail::class:
                $this->subscribeNewsletter($email);
                break;

            case NewsletterConfirmationEmail::class:
                $this->confirmNewsletterSubscription($email);
                break;

            case NewsletterUnsubscriptionEmail::class:
                $this->unsubscribeNewsletter($email);
                break;
        }        
    }

    public function submitContact(ContactSubmissionEmail $message)
    {
        $this->mailerGateway->send(
            $message->getSender(),
            $message->getReceiver(),
            $this->translator->trans('configuration.contact.submission.subject'),
            'emails/contact/submission.html.twig',
            [
                'phone' => $message->getPhone(),
                'content' => $message->getContent()
            ]
        );
    }
    
    public function subscribeNewsletter(NewsletterSubscriptionEmail $message)
    {
        $this->mailerGateway->send(
            $this->bag->get('noreply_email'),
            $message->getReceiver(),
            $this->translator->trans('configuration.newsletter.subscription.subject'),
            'emails/newsletter/subscription.html.twig',
            [
                'token' => $message->getToken()
            ]
        );
    }

    public function confirmNewsletterSubscription(NewsletterConfirmationEmail $message)
    {
        $this->mailerGateway->send(
            $this->bag->get('noreply_email'),
            $message->getReceiver(),
            $this->translator->trans('configuration.newsletter.confirmation.subject'),
            'emails/newsletter/confirmation.html.twig',
            [
                'id' => $message->getId()
            ]
        );
    }

    public function unsubscribeNewsletter(NewsletterUnsubscriptionEmail $message)
    {
        $this->mailerGateway->send(
            $this->bag->get('noreply_email'),
            $message->getReceiver(),
            $this->translator->trans('configuration.newsletter.unsubscription.subject'),
            'emails/newsletter/unsubscription.html.twig'
        );
    }
}
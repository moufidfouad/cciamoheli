<?php 

namespace App\Gateway;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerGateway implements MailerGatewayInterface
{
    /** @var MailerInterface */
    private $mailer;

    public function __construct(
        MailerInterface $mailer
    )
    {
        $this->mailer = $mailer;
    }

    public function send(string $from,string $to,string $subject,string $template,array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context)
        ;

        $this->mailer->send($email);
    }
}
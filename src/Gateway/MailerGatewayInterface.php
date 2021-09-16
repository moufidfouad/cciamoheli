<?php 

namespace App\Gateway;

interface MailerGatewayInterface
{
    public function send(string $from,string $to,string $subject,string $template,array $context = []): void;
}
<?php

namespace App\Messenger;

final class NewsletterSubscriptionEmail implements Email
{
    /** @var string */
    private $receiver;
    /** @var string */
    private $token;


    public function __construct(string $receiver,string $token)
    {
        $this->receiver = $receiver;
        $this->token = $token;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
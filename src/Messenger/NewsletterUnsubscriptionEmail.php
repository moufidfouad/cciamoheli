<?php

namespace App\Messenger;

final class NewsletterUnsubscriptionEmail implements Email
{
    /** @var string */
    private $receiver;


    public function __construct(string $receiver)
    {
        $this->receiver = $receiver;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }
}
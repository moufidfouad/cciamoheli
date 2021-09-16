<?php

namespace App\Messenger;

final class NewsletterConfirmationEmail implements Email
{
    /** @var string */
    private $receiver;
    /** @var int */
    private $id;


    public function __construct(string $receiver,int $id)
    {
        $this->receiver = $receiver;
        $this->id = $id;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
<?php

namespace App\Messenger;

final class ContactSubmissionEmail implements Email
{
    /** @var string */
    private $sender;
    /** @var string */
    private $receiver;
    /** @var string */
    private $phone;
    /** @var string */
    private $content;


    public function __construct(string $sender,string $receiver,string $phone, string $content)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->phone = $phone;
        $this->content = $content;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
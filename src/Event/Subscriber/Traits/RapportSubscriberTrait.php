<?php

namespace App\Event\Subscriber\Traits;

use App\Entity\Rapport;
use Symfony\Component\Security\Core\User\UserInterface;

trait RapportSubscriberTrait
{
    public function persistRapport(Rapport $rapport,UserInterface $user)
    {
        $rapport->setAgent($user);
    }
}
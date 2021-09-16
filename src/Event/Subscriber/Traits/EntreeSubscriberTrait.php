<?php

namespace App\Event\Subscriber\Traits;

use App\Tools\Utils;
use App\Entity\Entree;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait EntreeSubscriberTrait
{
    public function persistEntree(Entree $entree,UserPasswordHasherInterface $encoder)
    {
        $agent = $entree->getAgent();
        $agent->setEnabled(false);
        $fonction = $entree->getDestinationInterne();
        $agent->setFonctionCourante($fonction);

        Utils::setUserPasswordFromUsername($agent,$encoder);
    }

    public function removeEntree(Entree $entree)
    {
        $agent = $entree->getAgent();
        $agent->setFonctionCourante(null);
        $agent->setPublished(false);
    }
}
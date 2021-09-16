<?php

namespace App\Event\Subscriber\Traits;

use App\Entity\Es;
use App\Entity\Mutation;
use App\Tools\Constants;
use Doctrine\Common\Collections\Collection;
use LogicException;

trait MutationSubscriberTrait
{
    public function persistMutation(Mutation $mutation)
    {
        $agent = $mutation->getAgent();
        $fonctionDestination = $mutation->getDestinationInterne();
        $fonctionDerniere = $agent->getFonctionCourante();
        $esDerniere = $fonctionDerniere->getDestinations()->last();
        $esCollection = $agent->getEs()->filter(function(Es $es) use ($mutation){
            $forme = $es->getForme();
            return in_array($forme,[
                array_values(Constants::$ENTREE_CHOICES) + array_values(Constants::$MUTATION_CHOICES)
            ]) && $es->getId() ==! $mutation->getId();
        });

        $esDerniere = $esCollection instanceof Collection ? $esCollection->last() : null;

        if($esDerniere instanceof Es){
            $mutation->setOrigineInterne($agent->getFonctionCourante());
            $agent->setFonctionCourante($fonctionDestination);
            $esDerniere->setDateFin($mutation->getDateDebut());
        }else{
            throw new LogicException('Dernière fonction introuvable!!');
        }
    }
}
<?php 

namespace App\Event\Subscriber\Traits;

use App\Entity\Annulation;

trait AnnulationSubscriberTrait
{
    public function persistAnnulation(Annulation $annulation)
    {
        $agent = $annulation->getAgent();

        $agent->setEnabled(true);
    }
}
<?php 

namespace App\Event\Subscriber\Traits;

use App\Entity\Sortie;

trait SortieSubscriberTrait
{
    public function persistSortie(Sortie $sortie)
    {
        $agent = $sortie->getAgent();

        $agent->setEnabled(false);
        $agent->setPublished(false);
        $agent->setFonctionCourante(null);
    }
}
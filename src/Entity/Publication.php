<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use App\Tools\Constants;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication extends Annonce
{
    public function __construct()
    {
        parent::__construct();
        $this->forme = Constants::ANNONCE_PUBLICATION;
    }
}

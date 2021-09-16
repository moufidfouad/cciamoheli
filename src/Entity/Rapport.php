<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use App\Tools\Constants;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RapportRepository::class)
 */
class Rapport extends Annonce
{
    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="rapports")
     */
    private $agent;
    public function __construct()
    {
        parent::__construct();
        $this->forme = Constants::ANNONCE_RAPPORT;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }
}

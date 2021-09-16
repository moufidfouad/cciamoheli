<?php

namespace App\Entity;

use App\Repository\MutationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MutationRepository::class)
 */
class Mutation extends Es
{
}

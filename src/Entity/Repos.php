<?php

namespace App\Entity;

use App\Repository\ReposRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReposRepository::class)
 */
class Repos extends Es
{

}

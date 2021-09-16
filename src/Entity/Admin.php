<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
{
    public function __construct(string $username)
    {
        parent::__construct();
        $this->enabled = true;
        $this->username = $username;
        $this->addRole(User::ROLE_SUPER_ADMIN);
    }

    public function getFullname()
    {
        return sprintf('%s',ucfirst($this->username));
    }
}

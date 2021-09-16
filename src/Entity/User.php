<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Tools\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *  "ADMIN" = "Admin",
 *  "AGENT" = "Agent"
 * })
 */
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampTrait;
    
    const ROLE_DEFAULT = 'ROLE_USER';
    
    const ROLE_ADMIN_ARTICLE = 'ROLE_ADMIN_ARTICLE';
    const ROLE_ADMIN_PUBLICATION = 'ROLE_ADMIN_PUBLICATION';
    const ROLE_ADMIN_EVENEMENT = 'ROLE_ADMIN_EVENEMENT';
    const ROLE_ADMIN_BUREAU = 'ROLE_ADMIN_BUREAU';
    const ROLE_ADMIN_FONCTION = 'ROLE_ADMIN_FONCTION';
    const ROLE_ADMIN_ES = 'ROLE_ADMIN_ES';
    const ROLE_ADMIN_AGENT = 'ROLE_ADMIN_AGENT';
    const ROLE_ADMIN_MISSION = 'ROLE_ADMIN_MISSION';
    const ROLE_ADMIN_ACTIVITE = 'ROLE_ADMIN_ACTIVITE';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var string|null
     */
    protected $plainPassword;

    abstract function getFullname();

    public function __construct()
    {
        $this->addRole(static::ROLE_DEFAULT);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function addRole(string $role)
    {
        if(substr($role, 0, 5) !== 'ROLE_') {
            throw new \InvalidArgumentException("Chaque role doit commencer par 'ROLE_'");
        }

        if(!in_array($role,$this->roles,true)){
            array_push($this->roles,$role);
            return true;
        }
        return false;
    }

    public function hasRole($role)
    {
        if(is_string($role)){
            return in_array(strtoupper($role),$this->roles);
        }
        
        if(is_array($role)){
            foreach($role as $v){
                if(in_array(strtoupper($v),$this->roles)){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        if(!empty($roles)){
            foreach ($roles as $role)
            {
                $this->addRole($role);
            }
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEnabled(): ?bool
    {
        return (bool) $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}

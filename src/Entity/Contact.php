<?php 

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact 
{
    /** 
     * @var string|null 
     * @Assert\NotNull()
     */
    private $name;

    /** 
     * @var string|null 
     * @Assert\NotNull()
     * @Assert\Email()
     */
    private $email;

    /** @var string|null */
    private $phone;

    /** 
     * @var string|null 
     * @Assert\NotNull()
     */
    private $message;

    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
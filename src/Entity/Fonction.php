<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
use App\Tools\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=FonctionRepository::class)
 */
class Fonction
{
    use TimestampTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"titre"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Es::class, mappedBy="origineInterne")
     */
    private $origines;

    /**
     * @ORM\OneToMany(targetEntity=Es::class, mappedBy="destinationInterne")
     */
    private $destinations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity=Bureau::class, inversedBy="fonctions")
     */
    private $bureau;

    /**
     * @ORM\OneToOne(targetEntity=Agent::class, inversedBy="fonctionCourante", cascade={"persist", "remove"})
     */
    private $agent;

    public function __construct()
    {
        $this->origines = new ArrayCollection();
        $this->destinations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Es[]
     */
    public function getOrigines(): Collection
    {
        return $this->origines;
    }

    public function addOrigine(Es $origine): self
    {
        if (!$this->origines->contains($origine)) {
            $this->origines[] = $origine;
            $origine->setOrigineInterne($this);
        }

        return $this;
    }

    public function removeOrigine(Es $origine): self
    {
        if ($this->origines->removeElement($origine)) {
            // set the owning side to null (unless already changed)
            if ($origine->getOrigineInterne() === $this) {
                $origine->setOrigineInterne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Es[]
     */
    public function getDestinations(): Collection
    {
        return $this->destinations;
    }

    public function addDestination(Es $destination): self
    {
        if (!$this->destinations->contains($destination)) {
            $this->destinations[] = $destination;
            $destination->setDestinationInterne($this);
        }

        return $this;
    }

    public function removeDestination(Es $destination): self
    {
        if ($this->destinations->removeElement($destination)) {
            // set the owning side to null (unless already changed)
            if ($destination->getDestinationInterne() === $this) {
                $destination->setDestinationInterne(null);
            }
        }

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): self
    {
        $this->bureau = $bureau;

        return $this;
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

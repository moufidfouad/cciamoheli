<?php

namespace App\Entity;

use App\Repository\EsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EsRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *  "ENTREE" = "Entree",
 *  "MUTATION" = "Mutation",
 *  "REPOS" = "Repos",
 *  "SORTIE" = "Sortie",
 *  "ANNULATION" = "Annulation"
 * })
 */
abstract class Es
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $origineExterne;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateDebut;

    /**
     * @ORM\Column(type="string", length=25)
     */
    protected $forme;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="es")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $agent;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="es")
     */
    protected $documents;

    /**
     * @ORM\ManyToOne(targetEntity=Fonction::class, inversedBy="origines")
     */
    protected $origineInterne;

    /**
     * @ORM\ManyToOne(targetEntity=Fonction::class, inversedBy="destinations")
     */
    protected $destinationInterne;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigineExterne(): ?string
    {
        return $this->origineExterne;
    }

    public function setOrigineExterne(?string $origineExterne): self
    {
        $this->origineExterne = $origineExterne;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setEs($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getEs() === $this) {
                $document->setEs(null);
            }
        }

        return $this;
    }

    public function getOrigineInterne(): ?Fonction
    {
        return $this->origineInterne;
    }

    public function setOrigineInterne(?Fonction $origineInterne): self
    {
        $this->origineInterne = $origineInterne;

        return $this;
    }

    public function getDestinationInterne(): ?Fonction
    {
        return $this->destinationInterne;
    }

    public function setDestinationInterne(?Fonction $destinationInterne): self
    {
        $this->destinationInterne = $destinationInterne;

        return $this;
    }
}

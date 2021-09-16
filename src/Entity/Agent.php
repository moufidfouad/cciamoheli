<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AgentRepository::class)
 */
class Agent extends User
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"nom","prenom"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $genre;

    /**
     * @ORM\Column(type="date")
     */
    private $ddn;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $ldn;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @Vich\UploadableField(mapping="agent", fileNameProperty="image")
     * @var File|null
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity=Rapport::class, mappedBy="agent", orphanRemoval=true)
     */
    private $rapports;

    /**
     * @ORM\OneToMany(targetEntity=Es::class, mappedBy="agent", orphanRemoval=true)
     */
    private $es;

    /**
     * @ORM\OneToOne(targetEntity=Fonction::class, mappedBy="agent", cascade={"persist", "remove"})
     */
    private $fonctionCourante;

    public function __construct()
    {
        parent::__construct();
        $this->rapports = new ArrayCollection();
        $this->es = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFullname();
    }

    public function getFullname()
    {
        return sprintf('%s %s',$this->prenom,$this->nom);
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = ucfirst($nom);

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = ucfirst($prenom);

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDdn(): ?\DateTimeInterface
    {
        return $this->ddn;
    }

    public function setDdn(\DateTimeInterface $ddn): self
    {
        $this->ddn = $ddn;

        return $this;
    }

    public function getLdn(): ?string
    {
        return $this->ldn;
    }

    public function setLdn(?string $ldn): self
    {
        $this->ldn = $ldn;

        return $this;
    }

    public function getNin(): ?string
    {
        return $this->nin;
    }

    public function setNin(string $nin): self
    {
        $this->nin = $nin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return Collection|Es[]
     */
    public function getEs(): Collection
    {
        return $this->es;
    }

    public function addE(Es $e): self
    {
        if (!$this->es->contains($e)) {
            $this->es[] = $e;
            $e->setAgent($this);
        }

        return $this;
    }

    public function removeE(Es $e): self
    {
        if ($this->es->removeElement($e)) {
            // set the owning side to null (unless already changed)
            if ($e->getAgent() === $this) {
                $e->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rapport[]
     */
    public function getRapports(): Collection
    {
        return $this->rapports;
    }

    public function addRapport(Rapport $rapport): self
    {
        if (!$this->rapports->contains($rapport)) {
            $this->rapports[] = $rapport;
            $rapport->setAgent($this);
        }

        return $this;
    }

    public function removeRapport(Rapport $rapport): self
    {
        if ($this->rapports->removeElement($rapport)) {
            // set the owning side to null (unless already changed)
            if ($rapport->getAgent() === $this) {
                $rapport->setAgent(null);
            }
        }

        return $this;
    }

    public function getFonctionCourante(): ?Fonction
    {
        return $this->fonctionCourante;
    }

    public function setFonctionCourante(?Fonction $fonctionCourante): self
    {
        // unset the owning side of the relation if necessary
        if ($fonctionCourante === null && $this->fonctionCourante !== null) {
            $this->fonctionCourante->setAgent(null);
        }

        // set the owning side of the relation if necessary
        if ($fonctionCourante !== null && $fonctionCourante->getAgent() !== $this) {
            $fonctionCourante->setAgent($this);
        }

        $this->fonctionCourante = $fonctionCourante;

        return $this;
    }
}

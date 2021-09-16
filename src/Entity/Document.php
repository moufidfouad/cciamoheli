<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use App\Tools\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @Vich\Uploadable()
 */
class Document
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
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="documents")
     */
    private $annonce;

    /**
     * @Vich\UploadableField(mapping="document", fileNameProperty="path")
     * @var File|null
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Es::class, inversedBy="documents")
     */
    private $es;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

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

    public function getEs(): ?Es
    {
        return $this->es;
    }

    public function setEs(?Es $es): self
    {
        $this->es = $es;

        return $this;
    }
}

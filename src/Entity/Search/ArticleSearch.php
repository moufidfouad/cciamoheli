<?php

namespace App\Entity\Search;

use App\Entity\Tag;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class ArticleSearch
{
    const PAGE = 1;
    const LIMIT = 3;
    /**  @var int */
    private $page;

    /**  @var int */
    private $limit;

    /** var string|null */
    private $query;

    /** var Collection|Tag[] */
    private $tags;

    public function __construct()
    {
        $this->page = self::PAGE;
        $this->limit = self::LIMIT;
        $this->tags = new ArrayCollection();
    }


    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery($query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
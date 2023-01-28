<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $category_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Hymn::class, orphanRemoval: true)]
    private Collection $hymns;

    public function __construct()
    {
        $this->hymns = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->category_id;
    }

    public function getCategoryId(): ?string
    {
        return $this->category_id;
    }

    public function setCategoryId(string $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Hymn>
     */
    public function getHymns(): Collection
    {
        return $this->hymns;
    }

    public function addHymn(Hymn $hymn): self
    {
        if (!$this->hymns->contains($hymn)) {
            $this->hymns->add($hymn);
            $hymn->setCategory($this);
        }

        return $this;
    }

    public function removeHymn(Hymn $hymn): self
    {
        if ($this->hymns->removeElement($hymn)) {
            // set the owning side to null (unless already changed)
            if ($hymn->getCategory() === $this) {
                $hymn->setCategory(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\HymnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HymnRepository::class)]
class Hymn
{
    #[ORM\Id]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hymnId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'hymns')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'category_id', nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'hymn', targetEntity: Couplet::class, orphanRemoval: true)]
    private Collection $couplets;

    public function __construct()
    {
        $this->couplets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->hymnId;
    }

    public function getHymnId(): ?int
    {
        return $this->hymnId;
    }

    public function setHymnId(int $hymnId): self
    {
        $this->hymnId = $hymnId;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Couplet>
     */
    public function getCouplets(): Collection
    {
        return $this->couplets;
    }

    public function addCouplet(Couplet $couplet): self
    {
        if (!$this->couplets->contains($couplet)) {
            $this->couplets->add($couplet);
            $couplet->setHymnId($this);
        }

        return $this;
    }

    public function removeCouplet(Couplet $couplet): self
    {
        if ($this->couplets->removeElement($couplet)) {
            // set the owning side to null (unless already changed)
            if ($couplet->getHymn() === $this) {
                $couplet->setHymnId(null);
            }
        }

        return $this;
    }
}

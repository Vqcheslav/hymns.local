<?php

namespace App\Entity;

use App\Repository\HymnRepository;
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

    #[ORM\Column(type: Types::TEXT)]
    private ?string $chorus = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $couplets = null;

    #[ORM\ManyToOne(inversedBy: 'hymns')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'category_id', nullable: false)]
    private ?Category $category = null;

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

    public function getChorus(): ?string
    {
        return $this->chorus;
    }

    public function setChorus(string $chorus): self
    {
        $this->chorus = $chorus;

        return $this;
    }

    public function getCouplets(): ?string
    {
        return $this->couplets;
    }

    public function setCouplets(string $couplets): self
    {
        $this->couplets = $couplets;

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
}

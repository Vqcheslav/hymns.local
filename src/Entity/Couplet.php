<?php

namespace App\Entity;

use App\Repository\CoupletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoupletRepository::class)]
class Couplet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'couplets')]
    #[ORM\JoinColumn(name: 'hymn_id', referencedColumnName: 'hymn_id', nullable: false)]
    private ?Hymn $hymn = null;

    #[ORM\Column(length: 2000)]
    private ?string $couplet = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\Column]
    private ?bool $isChorus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHymn(): ?Hymn
    {
        return $this->hymn;
    }

    public function setHymnId(?Hymn $hymn): self
    {
        $this->hymn = $hymn;

        return $this;
    }

    public function getCouplet(): ?string
    {
        return $this->couplet;
    }

    public function setCouplet(string $couplet): self
    {
        $this->couplet = $couplet;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function isChorus(): ?bool
    {
        return $this->isChorus;
    }

    public function setIsChorus(bool $isChorus): self
    {
        $this->is_chorus = $isChorus;

        return $this;
    }
}

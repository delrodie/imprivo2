<?php

namespace App\Entity;

use App\Enum\SequenceDocType;
use App\Repository\SequenceDocRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SequenceDocRepository::class)]
class SequenceDoc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: SequenceDocType::class)]
    private ?SequenceDocType $type = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $prefix = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $format = "{prefix}-{YYYY}-{NNNN}";

    #[ORM\Column(nullable: true)]
    private ?int $compteur = 0;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?SequenceDocType
    {
        return $this->type;
    }

    public function setType(?SequenceDocType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(?int $compteur): static
    {
        $this->compteur = $compteur;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }
}

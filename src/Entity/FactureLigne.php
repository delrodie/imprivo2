<?php

namespace App\Entity;

use App\Repository\FactureLigneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureLigneRepository::class)]
class FactureLigne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $designation = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixUnitaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $montant = null;

    #[ORM\Column(nullable: true)]
    private ?array $detais = null;

    #[ORM\ManyToOne]
    private ?UniteMesure $uom = null;

    #[ORM\ManyToOne(inversedBy: 'lignes')]
    private ?Facture $facture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?int
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?int $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDetais(): ?array
    {
        return $this->detais;
    }

    public function setDetais(?array $detais): static
    {
        $this->detais = $detais;

        return $this;
    }

    public function getUom(): ?UniteMesure
    {
        return $this->uom;
    }

    public function setUom(?UniteMesure $uom): static
    {
        $this->uom = $uom;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }
}

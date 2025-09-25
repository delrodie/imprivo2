<?php

namespace App\Entity;

use App\Enum\FactureStatut;
use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Client $client = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalHT = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalTVA = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalTTC = null;

    #[ORM\Column(nullable: true)]
    private ?float $tauxTVA = null;

    #[ORM\Column(nullable: true)]
    private ?float $remise = null;

    #[ORM\Column(enumType: FactureStatut::class)]
    private ?FactureStatut $statut = FactureStatut::BROUILLON;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $updatedBy = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Devis $devis = null;

    /**
     * @var Collection<int, FactureLigne>
     */
    #[ORM\OneToMany(targetEntity: FactureLigne::class, mappedBy: 'facture')]
    private Collection $lignes;

    /**
     * @var Collection<int, FactureLog>
     */
    #[ORM\OneToMany(targetEntity: FactureLog::class, mappedBy: 'facture')]
    private Collection $logs;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalHT(): ?int
    {
        return $this->totalHT;
    }

    public function setTotalHT(?int $totalHT): static
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getTotalTVA(): ?int
    {
        return $this->totalTVA;
    }

    public function setTotalTVA(?int $totalTVA): static
    {
        $this->totalTVA = $totalTVA;

        return $this;
    }

    public function getTotalTTC(): ?int
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(?int $totalTTC): static
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getTauxTVA(): ?float
    {
        return $this->tauxTVA;
    }

    public function setTauxTVA(?float $tauxTVA): static
    {
        $this->tauxTVA = $tauxTVA;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getStatut(): ?FactureStatut
    {
        return $this->statut;
    }

    public function setStatut(?FactureStatut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * @return Collection<int, FactureLigne>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(FactureLigne $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setFacture($this);
        }

        return $this;
    }

    public function removeLigne(FactureLigne $ligne): static
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getFacture() === $this) {
                $ligne->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FactureLog>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(FactureLog $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setFacture($this);
        }

        return $this;
    }

    public function removeLog(FactureLog $log): static
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getFacture() === $this) {
                $log->setFacture(null);
            }
        }

        return $this;
    }
}

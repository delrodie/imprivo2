<?php

namespace App\Entity;

use App\Enum\DevisStatut;
use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalHT = 0;

    #[ORM\Column(nullable: true)]
    private ?int $totalTVA = 0;

    #[ORM\Column(nullable: true)]
    private ?int $totalTTC = 0;

    #[ORM\Column(enumType: DevisStatut::class)]
    private ?DevisStatut $statut = DevisStatut::BROUILLON;

    #[ORM\ManyToOne]
    private ?Client $client = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $updatedBy = null;

    /**
     * @var Collection<int, DevisLigne>
     */
    #[ORM\OneToMany(targetEntity: DevisLigne::class, mappedBy: 'devis', cascade: ['persist', 'remove'])]
    private Collection $lignes;

    #[ORM\ManyToOne]
    private ?Representant $contactClient = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $noteInterne = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $noteClient = null;

    #[ORM\Column(nullable: true)]
    private ?float $remise = null;

    #[ORM\Column(nullable: true)]
    private ?float $tauxTVA = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sendedAt = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $sendedBy = null;

    /**
     * @var Collection<int, DevisLog>
     */
    #[ORM\OneToMany(targetEntity: DevisLog::class, mappedBy: 'devis')]
    private Collection $logs;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->uuid = Uuid::v4();
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatut(): ?DevisStatut
    {
        return $this->statut;
    }

    public function setStatut(?DevisStatut $statut): static
    {
        $this->statut = $statut;

        return $this;
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

    /**
     * @return Collection<int, DevisLigne>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(DevisLigne $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setDevis($this);
        }

        return $this;
    }

    public function removeLigne(DevisLigne $ligne): static
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getDevis() === $this) {
                $ligne->setDevis(null);
            }
        }

        return $this;
    }

    public function getContactClient(): ?Representant
    {
        return $this->contactClient;
    }

    public function setContactClient(?Representant $contactClient): static
    {
        $this->contactClient = $contactClient;

        return $this;
    }

    public function getNoteInterne(): ?string
    {
        return $this->noteInterne;
    }

    public function setNoteInterne(?string $noteInterne): static
    {
        $this->noteInterne = $noteInterne;

        return $this;
    }

    public function getNoteClient(): ?string
    {
        return $this->noteClient;
    }

    public function setNoteClient(?string $noteClient): static
    {
        $this->noteClient = $noteClient;

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

    public function getTauxTVA(): ?float
    {
        return $this->tauxTVA;
    }

    public function setTauxTVA(?float $tauxTVA): static
    {
        $this->tauxTVA = $tauxTVA;

        return $this;
    }

    public function getSendedAt(): ?\DateTimeImmutable
    {
        return $this->sendedAt;
    }

    public function setSendedAt(?\DateTimeImmutable $sendedAt): static
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }

    public function getSendedBy(): ?string
    {
        return $this->sendedBy;
    }

    public function setSendedBy(?string $sendedBy): static
    {
        $this->sendedBy = $sendedBy;

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

    /**
     * @return Collection<int, DevisLog>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(DevisLog $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setDevis($this);
        }

        return $this;
    }

    public function removeLog(DevisLog $log): static
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getDevis() === $this) {
                $log->setDevis(null);
            }
        }

        return $this;
    }
}

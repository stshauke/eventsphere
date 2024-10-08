<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id")]
    private ?int $id = null;

    #[ORM\Column(name: "nom_evenement", length: 255, nullable: true)]
    private ?string $nomEvenement = null;

    #[ORM\Column(name: "description_evenement", length: 255, nullable: true)]
    private ?string $descriptionEvenement = null;

    #[ORM\Column(name: "date_evenement", type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(name: "lieu_evenement", length: 255, nullable: true)]
    private ?string $lieuEvenement = null;

    #[ORM\Column(name: "nb_max_participants", nullable: true)]
    private ?int $nbMaxParticipants = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->nomEvenement ;
    }

    public function setNomEvenement(?string $nomEvenement ): static
    {
        $this->nomEvenement = $nomEvenement ;

        return $this;
    }

    public function getDescriptionEvenement(): ?string
    {
        return $this->descriptionEvenement ;
    }

    public function setDescriptionEvenement(?string $descriptionEvenement ): static
    {
        $this->descriptionEvenement  = $descriptionEvenement ;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement ;
    }

    public function setDateEvenement(?\DateTimeInterface $dateEvenement ): static
    {
        $this->dateEvenement  = $dateEvenement ;

        return $this;
    }

    public function getLieuEvenement(): ?string
    {
        return $this->lieuEvenement ;
    }

    public function setLieuEvenement(?string $lieuEvenement ): static
    {
        $this->lieuEvenement  = $lieuEvenement ;

        return $this;
    }

    public function getNbMaxParticipants(): ?int
    {
        return $this->nbMaxParticipants;
    }

    public function setNbMaxParticipants(?int $nbMaxParticipants): static
    {
        $this->nbMaxParticipants = $nbMaxParticipants;

        return $this;
    }
}

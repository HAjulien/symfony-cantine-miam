<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CritiqueRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: CritiqueRepository::class)]
#[ORM\UniqueConstraint(
    name:'unique_id',
    columns: ['utilisateur_id', 'produit_id']
)]

#[ApiResource (
    normalizationContext: ['groups' => ['read:comment']],
    )]
    
class Critique
{

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('note', new Assert\Range([
            'min' => 0,
            'max' => 5,
            'notInRangeMessage' => 'note entre {{ min }} et {{ max }}',
        ]));
    }


    #[Groups(["read:comment"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["read:comment", "lire:produits"])]
    #[ORM\Column(type: 'float')]
    private $note;

    #[Groups(["read:comment", "lire:produits"])]
    #[ORM\Column(type: 'text', nullable: true)]
    private $contenu;

    #[Groups(["read:comment", "lire:produits"])]
    #[ORM\Column(type: 'datetime')]
    private $createAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'critiques')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:comment", "lire:produits"])]
    private $utilisateur;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'critiques')]
    #[ORM\JoinColumn(nullable: false)]
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}

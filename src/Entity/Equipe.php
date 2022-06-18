<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EquipeRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Index(name: 'equipe', columns: ['nom', 'contenu', 'prenom', 'surnom'], flags: ['fulltext'])]
#[ApiResource (
    attributes: ["pagination_items_per_page" => 3],
    collectionOperations:["get"],
    itemOperations:[
        "get" =>[
            "controller" => NotFoundAction::class,
            "openapi_context" =>[
                "summary" => "hidden",
            ],
            "read" => false,
            "output" => false
        ]
    ],
    normalizationContext: ['groups' => ['read:equipe']],

    )]
    
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'string', length: 100)]
    private $prenom;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'string', length: 120, nullable: true)]
    private $surnom;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'text')]
    private $contenu;

    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(type: 'datetime')]
    private $createAt;

    #[Groups(["read:equipe"])]
    #[ORM\Column(type: 'string', length: 140)]
    private $imageDescription;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'equipes')]
    #[Groups(["read:equipe"])]
    #[ORM\JoinColumn(nullable: false)]
    private $Utilisateur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $bgcolor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSurnom(): ?string
    {
        return $this->surnom;
    }

    public function setSurnom(?string $surnom): self
    {
        $this->surnom = $surnom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
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

    public function getImageDescription(): ?string
    {
        return $this->imageDescription;
    }

    public function setImageDescription(string $imageDescription): self
    {
        $this->imageDescription = $imageDescription;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?User $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getBgcolor(): ?string
    {
        return $this->bgcolor;
    }

    public function setBgcolor(?string $bgcolor): self
    {
        $this->bgcolor = $bgcolor;

        return $this;
    }
}

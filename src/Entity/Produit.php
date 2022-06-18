<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use App\Repository\ProduitRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Index(name: 'produit', columns: ['nom', 'description'], flags: ['fulltext'])]
#[ApiResource (
    attributes: ["pagination_items_per_page" => 10],
    collectionOperations:["get"],
    itemOperations:["get"],
    normalizationContext: ['groups' => ['lire:produits']],
    )]

class Produit
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'string', length: 110, unique: true)]
    private $nom;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'text')]
    private $description;

    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(type: 'datetime')]
    private $createAt;

    #[ORM\Column(type: 'float')]
    private $prixAchat;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'float')]
    private $prixVente;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'string', length: 150)]
    private $image;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'string', length: 150)]
    private $altImage;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'boolean')]
    private $selectionner;

    #[Groups(["lire:produits"])]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[Gedmo\Slug(fields: ['nom'])]
    #[ORM\Column(type: 'string', length: 150)]
    private $slug;

    #[Groups(["lire:produits"])]
    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Critique::class, orphanRemoval: true)]
    private $critiques;

    #[Groups(["lire:produits"])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $JourPrevu;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Selection::class)]
    private $selections;


    public function __construct()
    {
        $this->critiques = new ArrayCollection();
        $this->selections = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

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

    public function getAltImage(): ?string
    {
        return $this->altImage;
    }

    public function setAltImage(string $altImage): self
    {
        $this->altImage = $altImage;

        return $this;
    }

    public function isSelectionner(): ?bool
    {
        return $this->selectionner;
    }

    public function setSelectionner(bool $selectionner): self
    {
        $this->selectionner = $selectionner;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Critique>
     */
    public function getCritiques(): Collection
    {
        return $this->critiques;
    }

    public function addCritique(Critique $critique): self
    {
        if (!$this->critiques->contains($critique)) {
            $this->critiques[] = $critique;
            $critique->setProduit($this);
        }

        return $this;
    }

    public function removeCritique(Critique $critique): self
    {
        if ($this->critiques->removeElement($critique)) {
            // set the owning side to null (unless already changed)
            if ($critique->getProduit() === $this) {
                $critique->setProduit(null);
            }
        }

        return $this;
    }

    public function getJourPrevu(): ?int
    {
        return $this->JourPrevu;
    }

    public function setJourPrevu(?int $JourPrevu): self
    {
        $this->JourPrevu = $JourPrevu;

        return $this;
    }

    /**
     * @return Collection<int, Selection>
     */
    public function getSelections(): Collection
    {
        return $this->selections;
    }

    public function addSelection(Selection $selection): self
    {
        if (!$this->selections->contains($selection)) {
            $this->selections[] = $selection;
            $selection->setProduit($this);
        }

        return $this;
    }

    public function removeSelection(Selection $selection): self
    {
        if ($this->selections->removeElement($selection)) {
            // set the owning side to null (unless already changed)
            if ($selection->getProduit() === $this) {
                $selection->setProduit(null);
            }
        }

        return $this;
    }

}

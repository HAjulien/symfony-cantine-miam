<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use App\Repository\CategoryRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Index(name: 'category', columns: ['nom'], flags: ['fulltext'])]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80)]
    private $nom;
    
    #[Gedmo\Slug(fields: ['nom'])]
    #[ORM\Column(type: 'string', length: 120)]
    private $slug;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    // public function setSlug(string $slug): self
    // {
    //     $this->slug = $slug;

    //     return $this;
    // }
}

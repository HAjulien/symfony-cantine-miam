<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Action\NotFoundAction;
use App\Repository\ImageCarouselRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: ImageCarouselRepository::class)]
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
    )]

class ImageCarousel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 200)]
    private $image;

    #[ORM\Column(type: 'string', length: 150)]
    private $descriptionImage;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescriptionImage(): ?string
    {
        return $this->descriptionImage;
    }

    public function setDescriptionImage(string $descriptionImage): self
    {
        $this->descriptionImage = $descriptionImage;

        return $this;
    }
}

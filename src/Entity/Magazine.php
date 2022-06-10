<?php

namespace App\Entity;

use App\Repository\MagazineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MagazineRepository::class)]
class Magazine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\NotBlank(message: 'Le titre du magazine est obligatoire')]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Le prix est obligatoire')]
    private $price;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank(message: 'La date est obligatoire')]
    private $created_at;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'magazines')]
    #[ORM\JoinColumn(nullable: false)]
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
#[ORM\Table(name: 'favorites')]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Product::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?\App\Entity\Product $product = null;

    #[ORM\ManyToOne(targetEntity: \App\Entity\User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?\App\Entity\User $user = null;

    #[ORM\Column(type:"boolean", options: ['default' => true])]
    private bool $stillLiked = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?\App\Entity\Product
    {
        return $this->product;
    }

    public function setProduct(\App\Entity\Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getUser(): ?\App\Entity\User
    {
        return $this->user;
    }

    public function setUser(\App\Entity\User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getStillLiked(): bool
    {
        return $this->stillLiked;
    }

    public function setStillLiked(bool $stillLiked): self
    {
        $this->stillLiked = $stillLiked;
        return $this;
    }
}

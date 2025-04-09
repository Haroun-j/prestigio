<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProductReference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $referenceCode = null;

    #[ORM\Column]
    private bool $isVariant = false;

    #[ORM\OneToOne(targetEntity: Product::class, inversedBy: 'productReference')]
    private ?Product $product = null;

    #[ORM\OneToOne(targetEntity: ProductVariation::class, inversedBy: 'productReference')]
    private ?ProductVariation $productVariation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceCode(): ?string
    {
        return $this->referenceCode;
    }

    public function setReferenceCode(string $referenceCode): static
    {
        $this->referenceCode = $referenceCode;
        return $this;
    }

    public function isVariant(): bool
    {
        return $this->isVariant;
    }

    public function setIsVariant(bool $isVariant): static
    {
        $this->isVariant = $isVariant;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getProductVariation(): ?ProductVariation
    {
        return $this->productVariation;
    }

    public function setProductVariation(?ProductVariation $variation): static
    {
        $this->productVariation = $variation;
        return $this;
    }
}

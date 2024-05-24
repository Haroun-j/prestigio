<?php

namespace App\Entity;

use App\Repository\ProductSizeRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProductSizeRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class ProductSize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productSizes')]
    private ?Product $product = null;
    
    #[ORM\ManyToOne(inversedBy: 'productSizes')]
    private ?ProductVariation $productVariation = null;

    #[ORM\ManyToOne(inversedBy: 'productSizes')]
    private ?AvailableSize $availableSize = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
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
    
    public function setProductVariation(?ProductVariation $productVariation): static
    {
        $this->productVariation = $productVariation;
        
        return $this;
    }
    public function getAvailableSize(): ?AvailableSize
    {
        return $this->availableSize;
    }

    public function setAvailableSize(?AvailableSize $availableSize): static
    {
        $this->availableSize = $availableSize;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

}

<?php

namespace App\Entity;

use App\Repository\AvailableSizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailableSizeRepository::class)]
class AvailableSize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string',length: 255)]
    private ?string $size = null;

    #[ORM\OneToMany(mappedBy: 'size', targetEntity: ProductSize::class)]
    private Collection $productSizes;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productVariations = new ArrayCollection();
        $this->productSizes = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->size;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, ProductSize>
     */
    public function getProductSizes(): Collection
    {
        return $this->productSizes;
    }

    public function addProductSize(ProductSize $productSize): static
    {
        if (!$this->productSizes->contains($productSize)) {
            $this->productSizes->add($productSize);
            $productSize->setSize($this);
        }

        return $this;
    }

    public function removeProductSize(ProductSize $productSize): static
    {
        if ($this->productSizes->removeElement($productSize)) {
            // set the owning side to null (unless already changed)
            if ($productSize->getSize() === $this) {
                $productSize->setSize(null);
            }
        }

        return $this;
    }
}

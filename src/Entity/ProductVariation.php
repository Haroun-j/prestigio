<?php

namespace App\Entity;

use App\Repository\ProductVariationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductVariationRepository::class)]
#[Broadcast]
#[Vich\Uploadable]
class ProductVariation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productVariations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $colorCode = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $Weight = null;

    #[ORM\Column(length: 255)]
    private ?string $CountryOfOrigin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $DateOfManufacture = null;

    #[ORM\Column]
    private ?int $availableStock = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $price = null;
    #[Vich\UploadableField(mapping: 'product_variation_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'productVariation', targetEntity: ProductReference::class, cascade: ['persist', 'remove'])]
    private ?ProductReference $productReference = null;

    #[ORM\OneToMany(mappedBy: 'productVariation', targetEntity: ProductSize::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $productSizes;

    #[ORM\OneToMany(mappedBy: 'productVariation', targetEntity: AdditionalProductImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $additionalProductImages;

    public function __construct()
    {
        $this->productSizes = new ArrayCollection();
        $this->additionalProductImages = new ArrayCollection();
        $this->availableStock = 0;
    }

    public function __toString(): string
    {
        $productName = $this->product ? $this->product->getName() : 'Unknown Product';
        $color = $this->color ?? 'No Color';

        return sprintf('%s_%s', $productName, $color);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductReference(): ?ProductReference
    {
        return $this->productReference;
    }

    public function setProductReference(?ProductReference $ref): static
    {
        $this->productReference = $ref;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function setColorCode(string $colorCode): static
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function getWeight(): ?string
    {
        return $this->Weight;
    }

    public function setWeight(string $Weight): static
    {
        $this->Weight = $Weight;

        return $this;
    }

    public function getCountryOfOrigin(): ?string
    {
        return $this->CountryOfOrigin;
    }

    public function setCountryOfOrigin(string $CountryOfOrigin): static
    {
        $this->CountryOfOrigin = $CountryOfOrigin;

        return $this;
    }

    public function getDateOfManufacture(): ?\DateTimeImmutable
    {
        return $this->DateOfManufacture;
    }

    public function setDateOfManufacture(\DateTimeImmutable $DateOfManufacture): static
    {
        $this->DateOfManufacture = $DateOfManufacture;

        return $this;
    }

    public function getAvailableStock(): ?int
    {
        return $this->availableStock;
    }

    public function setAvailableStock(int $availableStock): static
    {
        $this->availableStock = $availableStock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if(null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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
        if(!$this->productSizes->contains($productSize)) {
            $this->productSizes->add($productSize);
            $productSize->setProductVariation($this);
            $this->availableStock += $productSize->getQuantity();
        }

        return $this;
    }

    public function removeProductSize(ProductSize $productSize): static
    {
        if($this->productSizes->removeElement($productSize)) {
            // set the owning side to null (unless already changed)
            if($productSize->getProductVariation() === $this) {
                $productSize->setProductVariation(null);
            }
            $this->availableStock -= $productSize->getQuantity();
        }

        return $this;
    }

    public function calculateAvailableStock(): int
    {
        $totalStock = 0;
        foreach($this->productSizes as $productSize) {
            $totalStock += $productSize->getQuantity();
        }
        return $totalStock;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateAvailableStock()
    {
        $this->availableStock = $this->calculateAvailableStock();
    }

    /**
     * @return Collection<int, AdditionalProductImage>
     */
    public function getAdditionalProductImages(): Collection
    {
        return $this->additionalProductImages;
    }

    public function addAdditionalProductImage(AdditionalProductImage $image): static
    {
        if(!$this->additionalProductImages->contains($image)) {
            $this->additionalProductImages[] = $image;
            $image->setProductVariation($this);
        }

        return $this;
    }

    public function removeAdditionalProductImage(AdditionalProductImage $image): static
    {
        if($this->additionalProductImages->removeElement($image)) {
            if($image->getProductVariation() === $this) {
                $image->setProductVariation(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ApiResource(formats: ['jsonld'])]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial', 'id' => 'exact' ])]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;
    
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
    
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductVariation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $productVariations;
    

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductSize::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $productSizes;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: FeaturedProduct::class)]
    private Collection $featuredProducts;

    public function __construct()
    {
        $this->productVariations = new ArrayCollection();
        $this->productSizes = new ArrayCollection();
        $this->featuredProducts = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
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

    /**
     * @return Collection<int, ProductVariation>
     */
    public function getProductVariations(): Collection
    {
        return $this->productVariations;
    }

    public function addProductVariation(ProductVariation $productVariation): static
    {
        if (!$this->productVariations->contains($productVariation)) {
            $this->productVariations->add($productVariation);
            $productVariation->setProduct($this);
        }

        return $this;
    }

    public function removeProductVariation(ProductVariation $productVariation): static
    {
        if ($this->productVariations->removeElement($productVariation)) {
            // set the owning side to null (unless already changed)
            if ($productVariation->getProduct() === $this) {
                $productVariation->setProduct(null);
            }
        }

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
            $productSize->setProduct($this);
        }

        return $this;
    }

    public function removeProductSize(ProductSize $productSize): static
    {
        if ($this->productSizes->removeElement($productSize)) {
            // set the owning side to null (unless already changed)
            if ($productSize->getProduct() === $this) {
                $productSize->setProduct(null);
            }
        }

        return $this;
    }
    public function calculateAvailableStock(): int {
        $totalStock = 0;
        foreach ($this->productSizes as $productSize) {
            $totalStock += $productSize->getQuantity();
        }
        return $totalStock;
    }

    /**
     * @return Collection<int, FeaturedProduct>
     */
    public function getFeaturedProducts(): Collection
    {
        return $this->featuredProducts;
    }

    public function addFeaturedProduct(FeaturedProduct $featuredProduct): static
    {
        if (!$this->featuredProducts->contains($featuredProduct)) {
            $this->featuredProducts->add($featuredProduct);
            $featuredProduct->setRelation($this);
        }

        return $this;
    }

    public function removeFeaturedProduct(FeaturedProduct $featuredProduct): static
    {
        if ($this->featuredProducts->removeElement($featuredProduct)) {
            // set the owning side to null (unless already changed)
            if ($featuredProduct->getRelation() === $this) {
                $featuredProduct->setRelation(null);
            }
        }

        return $this;
    }
}

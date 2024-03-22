<?php
    
    namespace App\Entity;
    
    use App\Entity\Trait\CreatedAtTrait;
    use App\Repository\CouponsRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use App\Validator\Constraints as AppAssert;
    
    #[AppAssert\ExclusiveProductOrCategory]
    #[ORM\Entity(repositoryClass: CouponsRepository::class)]
    class Coupons
    {
        use CreatedAtTrait;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;
        
        #[ORM\Column(type: 'string', length: 10, unique: true)]
        private $code;
        
        #[ORM\Column(type: 'text')]
        private $description;
        
        #[ORM\Column(type: 'integer')]
        private $discount;
        
        #[ORM\ManyToOne(targetEntity: Product::class)]
        #[ORM\JoinColumn(nullable: true)]
        private $onProduct;
        
        #[ORM\ManyToOne(targetEntity: Category::class)]
        #[ORM\JoinColumn(nullable: true)]
        private $onCategory;
        
        #[ORM\Column(type: 'integer')]
        private $max_usage;
        
        #[ORM\Column(type: 'datetime')]
        private $validity;
        
        #[ORM\Column(type: 'boolean')]
        private $is_valid;
        
        #[ORM\ManyToOne(targetEntity: CouponsTypes::class, inversedBy: 'coupons')]
        #[ORM\JoinColumn(nullable: false)]
        private $coupons_types;
        
        #[ORM\OneToMany(mappedBy: 'coupons', targetEntity: Orders::class)]
        private $orders;
        
        public function __construct()
        {
            $this->orders = new ArrayCollection();
            $this->created_at = new \DateTimeImmutable();
        }
        public function __toString (): string
        {
            // Example, customize it according to your needs
            return sprintf('ID_%d/___%s',
                $this->id,
                $this->description,
//                $this->coupons_types->getName(),
//                $this->discount
            );
        }
        
        
        public function getId(): ?int
        {
            return $this->id;
        }
        
        public function getCode(): ?string
        {
            return $this->code;
        }
        
        public function setCode(string $code): self
        {
            $this->code = $code;
            
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
        
        public function getDiscount(): ?int
        {
            return $this->discount;
        }
        
        public function setDiscount(int $discount): self
        {
            $this->discount = $discount;
            
            return $this;
        }
        public function getOnProduct(): ?Product
        {
            return $this->onProduct;
        }
        
        public function setOnProduct(?Product $onProduct): self
        {
            $this->onProduct = $onProduct;
            return $this;
        }
        
        public function getOnCategory(): ?Category
        {
            return $this->onCategory;
        }
        
        public function setOnCategory(?Category $onCategory): self
        {
            $this->onCategory = $onCategory;
            return $this;
        }
        
        public function getMaxUsage(): ?int
        {
            return $this->max_usage;
        }
        
        public function setMaxUsage(int $max_usage): self
        {
            $this->max_usage = $max_usage;
            
            return $this;
        }
        
        public function getValidity(): ?\DateTimeInterface
        {
            return $this->validity;
        }
        
        public function setValidity(\DateTimeInterface $validity): self
        {
            $this->validity = $validity;
            
            return $this;
        }
        
        public function getIsValid(): ?bool
        {
            if (new \DateTime() > $this->validity) {
                return false;
            }
            
            return $this->is_valid;
        }
        
        public function setIsValid(bool $is_valid): self
        {
            $this->is_valid = $is_valid;
            
            return $this;
        }
        
        public function getCouponsTypes(): ?CouponsTypes
        {
            return $this->coupons_types;
        }
        
        public function setCouponsTypes(?CouponsTypes $coupons_types): self
        {
            $this->coupons_types = $coupons_types;
            
            return $this;
        }
        
        /**
         * @return Collection|Orders[]
         */
        public function getOrders(): Collection
        {
            return $this->orders;
        }
        
        public function addOrder(Orders $order): self
        {
            if (!$this->orders->contains($order)) {
                $this->orders[] = $order;
                $order->setCoupons($this);
            }
            
            return $this;
        }
        
        public function removeOrder(Orders $order): self
        {
            if ($this->orders->removeElement($order)) {
                // set the owning side to null (unless already changed)
                if ($order->getCoupons() === $this) {
                    $order->setCoupons(null);
                }
            }
            
            return $this;
        }
    }
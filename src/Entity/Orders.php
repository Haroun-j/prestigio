<?php
    
    namespace App\Entity;
    
    use App\Entity\Trait\CreatedAtTrait;
    use App\Repository\OrdersRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    
    #[ORM\Entity(repositoryClass: OrdersRepository::class)]
    class Orders
    {
        use CreatedAtTrait;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;
        
        #[ORM\Column(type: 'string', length: 20, unique: true)]
        private $reference;
        
        #[ORM\Column(type: 'string', length: 20)]
        private $transporterName = null;
        
        #[ORM\Column(type: 'integer')]
        private $transporterPrice;
        
        #[ORM\Column(type: 'boolean')]
        private $isPaid;
        
        #[ORM\Column(type: 'string', length: 20)]
        private $method;
        
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $stripeSessionId;
        #[ORM\ManyToOne(targetEntity: Coupons::class, inversedBy: 'orders')]
        private $coupons;
        
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private $user;
        
        #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
        private $ordersDetails;
        
        public function __construct ()
        {
            $this->ordersDetails = new ArrayCollection();
            $this->created_at = new \DateTimeImmutable();
        }
        
        public function getCreatedAt (): \DateTimeImmutable
        {
            return $this->created_at;
        }
        
        public function setCreatedAt (\DateTimeImmutable $created_at): void
        {
            $this->created_at = $created_at;
        }
        
        public function __toString (): string
        {
            return $this->reference;
        }
        
        public function getId (): ?int
        {
            return $this->id;
        }
        
        public function getReference (): ?string
        {
            return $this->reference;
        }
        
        public function setReference (string $reference): self
        {
            $this->reference = $reference;
            
            return $this;
        }
        
        public function getUser (): ?User
        {
            return $this->user;
        }
        
        public function setUser (?User $user): self
        {
            $this->user = $user;
            
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getTransporterName ()
        {
            return $this->transporterName;
        }
        
        /**
         * @param mixed $transporterName
         */
        public function setTransporterName ($transporterName): void
        {
            $this->transporterName = $transporterName;
        }
        
        /**
         * @return mixed
         */
        public function getTransporterPrice ()
        {
            return $this->transporterPrice;
        }
        
        /**
         * @param mixed $transporterPrice
         */
        public function setTransporterPrice ($transporterPrice): void
        {
            $this->transporterPrice = $transporterPrice;
        }
        
        /**
         * @return mixed
         */
        public function getIsPaid ()
        {
            return $this->isPaid;
        }
        
        /**
         * @param mixed $isPaid
         */
        public function setIsPaid ($isPaid): void
        {
            $this->isPaid = $isPaid;
        }
        
        /**
         * @return mixed
         */
        public function getMethod ()
        {
            return $this->method;
        }
        
        /**
         * @param mixed $method
         */
        public function setMethod ($method): void
        {
            $this->method = $method;
        }
        
        /**
         * @return string|null
         */
        public function getStripeSessionId (): ?string
        {
            return $this->stripeSessionId;
        }
        
        /**
         * @param string|null $stripeSessionId
         */
        public function setStripeSessionId (?string $stripeSessionId): void
        {
            $this->stripeSessionId = $stripeSessionId;
        }
        
        /**
         * @return Collection|OrdersDetails[]
         */
        public function getOrdersDetails (): Collection
        {
            return $this->ordersDetails;
        }
        
        public function addOrdersDetail (OrdersDetails $ordersDetail): self
        {
            if (!$this->ordersDetails->contains($ordersDetail)) {
                $this->ordersDetails[] = $ordersDetail;
                $ordersDetail->setOrders($this);
            }
            
            return $this;
        }
        
        public function removeOrdersDetail (OrdersDetails $ordersDetail): self
        {
            if ($this->ordersDetails->removeElement($ordersDetail)) {
                // set the owning side to null (unless already changed)
                if ($ordersDetail->getOrders() === $this) {
                    $ordersDetail->setOrders(null);
                }
            }
            
            return $this;
        }
        
        public function getOrderDetailsSummary (): string
        {
            return implode(', ', array_map(function ($detail) {
                return (string)$detail;
            }, $this->ordersDetails->toArray()));
        }
        
        public function getCoupons (): ?Coupons
        {
            return $this->coupons;
        }
        
        public function setCoupons (?Coupons $coupons): self
        {
            $this->coupons = $coupons;
            
            return $this;
        }
    }

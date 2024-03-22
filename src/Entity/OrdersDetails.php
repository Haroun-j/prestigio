<?php
    
    namespace App\Entity;
    
    use App\Repository\OrdersDetailsRepository;
    use Doctrine\ORM\Mapping as ORM;
    
    #[ORM\Entity(repositoryClass: OrdersDetailsRepository::class)]
    class OrdersDetails
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;
        
        #[ORM\Column(type: 'integer')]
        private $quantity;
        
        #[ORM\Column(type: 'integer')]
        private $price;
        
        #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'ordersDetails')]
        #[ORM\JoinColumn(nullable: false)]
        private $orders;
        
        #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'ordersDetails')]
        #[ORM\JoinColumn(nullable: false)]
        private $product;
        
        public function __toString (): string
        {
            // Example, customize it according to your needs
            return sprintf('ID_%d....%d X %s   ,    %d € /UNIT ______',
                $this->id,
                $this->quantity,
                $this->product->getName(),
                $this->price
            );
        }
        
        public function getId (): ?int
        {
            return $this->id;
        }
        
        public function getQuantity (): ?int
        {
            return $this->quantity;
        }
        
        public function setQuantity (int $quantity): self
        {
            $this->quantity = $quantity;
            
            return $this;
        }
        
        public function getPrice (): ?int
        {
            return $this->price;
        }
        
        public function setPrice (int $price): self
        {
            $this->price = $price;
            
            return $this;
        }
        
        public function getOrders (): ?Orders
        {
            return $this->orders;
        }
        
        public function setOrders (?Orders $orders): self
        {
            $this->orders = $orders;
            
            return $this;
        }
        
        public function getProduct (): ?Product
        {
            return $this->product;
        }
        
        public function setProduct (?Product $product): self
        {
            $this->product = $product;
            
            return $this;
        }
    }

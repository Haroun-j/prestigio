<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $fullName = null;

    #[ORM\Column(type: 'string', length: 180)]
    #[Assert\Email()]
    #[Assert\Length(min: 2, max: 180)]
    private string $email;
    
    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 10)]
    #[Assert\Regex(
        pattern: '/^\d{10}$/',
        message: 'Le numéro de téléphone doit contenir exactement 10 chiffres.'
    )]
    private ?string $phone;

    #[ORM\Column(type: 'string', length: 180)]
    #[Assert\Length(min: 2, max: 180)]
    private string $company;
    
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $subject = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank()]
    private string $message;
    
    #[ORM\Column(type: 'boolean')]
    private bool $isClient = false;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $client = null;
    
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        
        return $this;
    }
    public function getCompany(): ?string
    {
        return $this->company;
    }
    
    public function setCompany(string $company): self
    {
        $this->company = $company;
        
        return $this;
    }
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIsClient(): bool
    {
        return $this->isClient;
    }


    public function setIsClient(bool $isClient): self
    {
        $this->isClient = $isClient;
        return $this;
    }
    
    public function getClient(): ?User
    {
        return $this->client;
    }
    
    public function setClient(?User $client): self
    {
        $this->client = $client;
        return $this;
    }
    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

<?php
// src/Entity/UserAvatar.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class UserAvatar implements \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // This File property is transient and must not be serialized.
    #[Vich\UploadableField(mapping: 'user_avatars', fileNameProperty: 'avatarName')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;
        if(null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setAvatarName(?string $avatarName): void
    {
        $this->avatarName = $avatarName;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // === Implementation of Serializable ===

    public function serialize(): string
    {

    }

    public function unserialize($serialized): void
    {
        $data = unserialize($serialized);
        $this->id = $data['id'] ?? null;
        $this->avatarName = $data['avatarName'] ?? null;
        $this->updatedAt = $data['updatedAt'] ?? null;
    }

    public function __serialize(): array
    {
        // TODO: Implement __serialize() method.
        return [];
    }

    public function __unserialize(array $data): void
    {
        // TODO: Implement __unserialize() method.
    }
}

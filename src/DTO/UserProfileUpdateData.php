<?php
// src/DTO/UserProfileUpdateData.php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserProfileUpdateData
{
    // GENERAL INFO (only updatable fields)
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    public ?string $lastName = null;

    #[Assert\NotNull(message: "La date de naissance est obligatoire.")]
    public ?\DateTimeInterface $dateOfBirth = null;

    #[Assert\NotBlank(message: "Le genre est obligatoire.")]
    #[Assert\Choice(choices: ["Masculin", "Féminin", "Autre"], message: "Veuillez sélectionner un genre valide.")]
    public ?string $gender = null;

    // Optional superhero nickname
    public ?string $heroNickname = null;

    // ADDRESS INFO
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    public ?string $streetAddress = null;

    public ?string $addressComplement = null;

    #[Assert\NotBlank(message: "Le code postal est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{5}$/", message: "Le code postal doit être composé de 5 chiffres.")]
    public ?string $postalCode = null;

    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    public ?string $city = null;

    public ?string $department = null;

    #[Assert\NotBlank(message: "Le pays est obligatoire.")]
    public ?string $country = 'France';

    // Optionally, newsletter subscription
    public ?bool $newsletter = false;
}

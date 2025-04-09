<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserAdditionalInfoData
{
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    public ?string $lastName = null;

    #[Assert\NotNull(message: "La date de naissance est obligatoire.")]
    public ?\DateTimeInterface $dateOfBirth = null;

    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/",
        message: "Le numéro de téléphone n'est pas valide."
    )]
    public ?string $phone = null;

    #[Assert\NotBlank(message: "Le genre est obligatoire.")]
    #[Assert\Choice(choices: ["Masculin", "Féminin", "Autre"], message: "Veuillez sélectionner un genre valide.")]
    public ?string $gender = null;

    // Champ optionnel fun (surnom de super-héros)
    public ?string $heroNickname = null;

    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    public ?string $streetAddress = null;

    // Complément d'adresse optionnel
    public ?string $addressComplement = null;

    #[Assert\NotBlank(message: "Le code postal est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{5}$/", message: "Le code postal doit être composé de 5 chiffres.")]
    public ?string $postalCode = null;

    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    public ?string $city = null;

    // Département optionnel
    public ?string $department = null;

    #[Assert\NotBlank(message: "Le pays est obligatoire.")]
    public ?string $country = 'France';

    #[Assert\IsTrue(message: "Vous devez confirmer que les informations sont correctes.")]
    public ?bool $confirmData = false;

    public ?bool $newsletter = false;
}

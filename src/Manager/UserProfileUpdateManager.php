<?php
// src/Manager/UserProfileUpdateManager.php
namespace App\Manager;

use App\DTO\UserProfileUpdateData;
use App\Entity\User;

class UserProfileUpdateManager
{

    public function reverseTransform(User $user): UserProfileUpdateData
    {
        $dto = new UserProfileUpdateData();
        // General Info
        $dto->firstName = $user->getFirstName();
        $dto->lastName = $user->getLastName();
        $dto->dateOfBirth = $user->getDateOfBirth();
        $dto->gender = $user->getGender();
        $dto->heroNickname = $user->getHeroNickname();
        // Address Info
        $dto->streetAddress = $user->getStreetAddress();
        $dto->addressComplement = $user->getAddressComplement();
        $dto->postalCode = $user->getPostalCode();
        $dto->city = $user->getCity();
        $dto->department = $user->getDepartment();
        $dto->country = $user->getCountry();
        $dto->newsletter = $user->getNewsletter();
        return $dto;
    }


    public function transform(UserProfileUpdateData $dto, User $user): User
    {
        // General Info
        $user->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setDateOfBirth($dto->dateOfBirth)
            ->setGender($dto->gender)
            ->setHeroNickname($dto->heroNickname);
        // Address Info
        $user->setStreetAddress($dto->streetAddress)
            ->setAddressComplement($dto->addressComplement)
            ->setPostalCode($dto->postalCode)
            ->setCity($dto->city)
            ->setDepartment($dto->department)
            ->setCountry($dto->country)
            ->setNewsletter($dto->newsletter);
        return $user;
    }
}

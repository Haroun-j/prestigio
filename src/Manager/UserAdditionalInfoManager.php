<?php

namespace App\Manager;

use App\DTO\UserAdditionalInfoData;
use App\Entity\User;

class UserAdditionalInfoManager
{

    public function transform(UserAdditionalInfoData $dto, User $user): User
    {
        $roles[] = 'ROLE_USER';

        $user->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setDateOfBirth($dto->dateOfBirth)
            ->setPhone($dto->phone)
            ->setGender($dto->gender)
            ->setHeroNickname($dto->heroNickname)
            ->setStreetAddress($dto->streetAddress)
            ->setAddressComplement($dto->addressComplement)
            ->setPostalCode($dto->postalCode)
            ->setCity($dto->city)
            ->setDepartment($dto->department)
            ->setCountry($dto->country)
            ->setNewsletter($dto->newsletter)
            ->setRoles($roles);

        return $user;
    }

    public function reverseTransform(User $user): UserAdditionalInfoData
    {
        $dto = new UserAdditionalInfoData();
        $dto->firstName = $user->getFirstName();
        $dto->lastName = $user->getLastName();
        $dto->dateOfBirth = $user->getDateOfBirth();
        $dto->phone = $user->getPhone();
        $dto->gender = $user->getGender();
        $dto->heroNickname = $user->getHeroNickname();
        $dto->streetAddress = $user->getStreetAddress();
        $dto->addressComplement = $user->getAddressComplement();
        $dto->postalCode = $user->getPostalCode();
        $dto->city = $user->getCity();
        $dto->department = $user->getDepartment();
        $dto->country = $user->getCountry();
        $dto->newsletter = $user->getNewsletter();

        return $dto;
    }
}

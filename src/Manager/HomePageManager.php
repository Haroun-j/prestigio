<?php

namespace App\Manager;

use App\Entity\User;

class HomePageManager extends AbstractManager
{

    public function hasFilledAllRequiredFields(User $user): bool
    {
        // Check if the phone field is non-empty.
        return !empty($user->getPhone());
    }
}

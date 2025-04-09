<?php

namespace App\Manager;

use App\Entity\Favorite;
use App\Entity\User;

class DashboardManager extends AbstractManager
{

    public function getFavoriteProductsData(User $user): array
    {
        $favorites = $this->entityManager->getRepository(Favorite::class)->findBy(['user' => $user]);

        $favoriteProducts = [];
        foreach($favorites as $favorite) {
            $product = $favorite->getProduct();
            $favoriteProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'imageName' => $product->getImageName(),
                'price' => $product->getPrice(),
                'referenceCode' => $product->getProductReference()->getReferenceCode(),
                'isStillLiked' => $favorite->getStillLiked(),
            ];
        }

        return $favoriteProducts;
    }
}

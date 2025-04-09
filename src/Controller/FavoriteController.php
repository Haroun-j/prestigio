<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{

    #[Route('/add-to-favorite', name: 'add_to_favorite', methods: "POST")]
    public function toggle(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $productId = $request->request->get('productId');
        if(!$productId) {
            return new JsonResponse(['success' => false, 'message' => 'No product ID provided'], 400);
        }

        $product = $em->getRepository(Product::class)->find($productId);
        if(!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Product not found'], 404);
        }

        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 403);
        }

        $favorite = $em->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if(!$favorite) {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setProduct($product);
            $favorite->setStillLiked(true);
            $em->persist($favorite);
            $newStatus = true;
        } else {
            $newStatus = !$favorite->getStillLiked();
            $favorite->setStillLiked($newStatus);
        }

        $em->flush();

        return new JsonResponse(['success' => true, 'isLiked' => $newStatus]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_details')]
    public function index(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Fetching the product entity based on the provided ID
        $product = $entityManager->find(Product::class, $id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Accessing properties of the product entity
        $name = $product->getName();
        $price = $product->getPrice();
        $category = $product->getCategory()->getLabel();
        $description = $product->getDescription();
        $imageName = $product->getImageName();

        // Putting all data into an array
        $data = [
            'name' => $name,
            'price' => $price,
            'category' => $category,
            'description' => $description,
            'image_name' => $imageName,
        ];
        return $this->render('products/store/product_template.html.twig', [
            'data' => $data,
        ]);
    }
}

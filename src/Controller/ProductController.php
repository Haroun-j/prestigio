<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductSize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_details')]
    public function index(EntityManagerInterface $entityManager, Packages $assetsManager, $id): Response
    {
        $product = $entityManager->find(Product::class, $id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        
        $productSizes = $entityManager->getRepository(ProductSize::class)
            ->findBy(['product' => $product]);
        
        $sizes = [];
        foreach ($productSizes as $productSize) {
            $sizes[] = $productSize->getAvailableSize()->getAvailableSize();
        }

        // Accessing properties of the product entity
        $id = $product->getId();
        $productName = $product->getName();
        $productColor = $product->getColorCode();
        $imagesDir = $this->getParameter('kernel.project_dir') . '/public/assets/images/products/more_images/racer_tr23_2d3149' ;
        $images = [];
        
        if (is_dir($imagesDir)) {
            $files = scandir($imagesDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $images[] = $file;
                }
            }
        }
//        dd($images);
        $price = $product->getPrice();
        $category = $product->getCategory()->getLabel();
        $description = $product->getDescription();
        
        // Putting all data into an array
        $data = [
            'id' => $id,
            'name' => $productName,
            'price' => $price,
            'category' => $category,
            'description' => $description,
            'sizes' => $sizes,
            'images' => $images,
        ];
        return $this->render('products/store/product_template.html.twig', [
            'data' => $data,
        ]);
    }
}

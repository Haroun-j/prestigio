<?php

namespace App\Controller;

use App\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{reference}', name: 'product_details')]
    public function index(ProductManager $productManager, string $reference): Response
    {
        $imagesDir = '/uploads/images/products/' ;
        $productData = $productManager->getStructuredProductData($reference, $imagesDir);
//dd($productData);
        if (!$productData) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('store/product_template.html.twig', [
            'data' => $productData,
            '_route' => 'productTemplate',
        ]);
    }

}

<?php

namespace App\Controller\Products;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    #[Route('/products', name: 'store')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Fetch all categories with their associated products
        $categories = $categoryRepository->findAllWithProducts();
//        dd($categories);
        return $this->render('products/store/product_list.html.twig', [
            'categories' => $categories,
        ]);
    }

//    /**
//     * @Route("/products/{id}", name="product_details")
//     */
//    public function show($id): Response
//    {
//        // Fetch the product details by ID from the database or any other source
//        $product = $this->getDoctrine()->getRepository(\App\Entity\Product::class)->find($id);
//
//        if (!$product) {
//            throw $this->createNotFoundException('Product not found');
//        }
//
//        return $this->render('product_details.html.twig', [
//            'product' => $product,
//        ]);
//    }
}

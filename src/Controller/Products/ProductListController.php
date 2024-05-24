<?php

namespace App\Controller\Products;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    #[Route('/store', name: 'store')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Fetch all categories with their associated products
        $categories = $categoryRepository->findAllWithProducts();
        
        $storeProductsData = [];
        
        foreach ($categories as $category) {
            $productsData = [];
            foreach ($category->getProducts() as $product) {
                $productVariations = $product->getProductVariations();
                $variationsData = [];
                $ProductSizesData = [];
                
                foreach ($product->getProductSizes() as $ProductSize) {
                    $ProductSizesData[] = [
                        'size' => $ProductSize->getAvailableSize()->getAvailableSize(),
                        'quantity' => $ProductSize->getQuantity()
                    ];
                }
                
                foreach ($productVariations as $variation) {
                    $pvSizesData = [];
                    foreach ($variation->getProductSizes() as $pvSize) {
                        $pvSizesData[] = [
                            'size' => $pvSize->getAvailableSize()->getAvailableSize(),
                            'quantity' => $pvSize->getQuantity()
                        ];
                    }
                    
                    $variationsData[] = [
                        'color' => $variation->getColor(),
                        'colorCode' => $variation->getColorCode(),
                        'weight' => $variation->getWeight(),
                        'countryOfOrigin' => $variation->getCountryOfOrigin(),
                        'dateOfManufacture' => $variation->getDateOfManufacture(),
                        'availableStock' => $variation->getAvailableStock(),
                        'additionalPrice' => $variation->getAdditionalPrice(),
                        'sizes' => $pvSizesData
                    ];
                }
                
                $productsData[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'color' => $product->getColor(),
                    'colorCode' => $product->getColorCode(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'sizes' => $ProductSizesData,
                    'imageName' => $product->getImageName(),
                    'variations' => $variationsData
                ];
            }
            
            $storeProductsData[] = [
                'label' => $category->getLabel(),
                'products' => $productsData
            ];
        }

        return $this->render('products/store/product_list.html.twig', [
            'storeData' => $storeProductsData,
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

<?php

namespace App\Controller;

use App\Entity\FeaturedProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user && !$user->isVerified()) {
            return $this->redirectToRoute('phone_verification');
        }
        // Retrieve all FeaturedProducts along with their related Product data
        $featuredProducts = $entityManager->getRepository(FeaturedProduct::class)
            ->findBy([], ['displayOrder' => 'ASC']);
        
        $featuredProductsData = [];
        
        foreach ($featuredProducts as $featuredProduct) {
            $product = $featuredProduct->getProduct();
            $productVariations = $product->getProductVariations();
            $variationsData = [];
            $ProductSizesData = [] ;
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
            
            $featuredProductsData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'color' => $product->getColor(),
                'colorCode' => $product->getColorCode(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'sizes' => $ProductSizesData,
                'variations' => $variationsData,
                'displayOrder' => $featuredProduct->getDisplayOrder()
            ];
        }
//        dd($featuredProductsData);
        return $this->render('index.html.twig', [
            'featuredProducts' => $featuredProductsData,
        ]);
    }
}

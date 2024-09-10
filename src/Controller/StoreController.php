<?php

namespace App\Controller;

use App\Manager\StoreManager;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'store')]
    public function index(StoreManager $storeManager): Response
    {

        $storeProductsData = $storeManager->getStoreProductsData();
        
        $priceRange = $storeManager->getPriceRange($storeProductsData);
        
        $filters = $storeManager->getFilters($storeProductsData, $priceRange);
        
        return $this->render('store/index.html.twig', [
            'storeData' => $storeProductsData,
            'filters' => $filters,
            'minPrice' => $priceRange['minPrice'],
            'maxPrice' => $priceRange['maxPrice']
        ]);
    }
}

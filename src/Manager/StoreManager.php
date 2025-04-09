<?php

namespace App\Manager;


use App\Entity\Category;
use App\Entity\Favorite;

class StoreManager extends AbstractManager
{
    public function getStoreProductsData($user): array
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAllWithProducts();
        $storeProductsData = [];

        foreach($categories as $category) {
            $productsData = [];
            foreach($category->getProducts() as $product) {
                $ProductSizesData = $this->getProductSizes($product);
                $variationsData = $this->getProductVariationsData($product);

                $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
                    'product' => $product,
                    'user' => $user,
                    'stillLiked' => true,
                ]);

                $productsData[] = [
                    'id' => $product->getId(),
                    'reference' => $product->getProductReference()->getReferenceCode(),
                    'name' => $product->getName(),
                    'color' => $product->getColor(),
                    'colorCode' => $product->getColorCode(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'sizes' => $ProductSizesData,
                    'imageName' => $product->getImageName(),
                    'variations' => $variationsData,
                    'isLiked' => (bool)$favorite,
                ];
            }

            $storeProductsData[] = [
                'label' => $category->getLabel(),
                'products' => $productsData,
            ];
        }

        return $storeProductsData;
    }

    private function getProductSizes($product): array
    {
        $ProductSizesData = [];
        foreach($product->getProductSizes() as $ProductSize) {
            $ProductSizesData[] = [
                'size' => $ProductSize->getAvailableSize()->getAvailableSize(),
                'quantity' => $ProductSize->getQuantity(),
            ];
        }

        return $ProductSizesData;
    }

    private function getProductVariationsData($product): array
    {
        $variationsData = [];
        foreach($product->getProductVariations() as $variation) {
            $pvSizesData = [];
            foreach($variation->getProductSizes() as $pvSize) {
                $pvSizesData[] = [
                    'size' => $pvSize->getAvailableSize()->getAvailableSize(),
                    'quantity' => $pvSize->getQuantity(),
                ];
            }

            $variationsData[] = [
                'id' => $variation->getId(),
                'reference' => $variation->getProductReference()->getReferenceCode(),
                'color' => $variation->getColor(),
                'colorCode' => $variation->getColorCode(),
                'weight' => $variation->getWeight(),
                'countryOfOrigin' => $variation->getCountryOfOrigin(),
                'dateOfManufacture' => $variation->getDateOfManufacture(),
                'availableStock' => $variation->getAvailableStock(),
                'price' => $variation->getPrice(),
                'sizes' => $pvSizesData,
            ];
        }

        return $variationsData;
    }

    public function getFilters(array $storeProductsData, array $priceRange): array
    {
        $categoryFilter = array_column($storeProductsData, 'label');

        return [
            [
                'id' => 0,
                'title' => 'Categories',
                'content' => $categoryFilter,
                'show' => true,
                'defaultLimit' => 4,
                'limitBy' => 4,
            ],
            [
                'id' => 1,
                'title' => 'Prix',
                'content' => [],
                'show' => true,
                'minPrice' => $priceRange['minPrice'],
                'maxPrice' => $priceRange['maxPrice'],
            ],
            [
                'id' => 2,
                'title' => 'Taille',
                'content' => ['One-Size', 'XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'show' => true,
                'defaultLimit' => 4,
                'limitBy' => 4,
            ],
            [
                'id' => 3,
                'title' => 'Couleur',
                'content' => ['White', 'Black', 'Grey', 'Purple', 'Blue', 'Green', 'Yellow', 'Orange', 'Red', 'Pink', 'Brown', 'Multicolor'],
                'show' => true,
                'defaultLimit' => 6,
                'limitBy' => 6,
                'color' => ['linear-gradient(45deg, rgba(247,250,255,1) 0%, rgba(255,255,255,1) 50%)', '#292c36', '#929abe', '#8667f7', '#5c9dff', '#9bec3e', '#ffe761', '#ffbc58', '#ff6f6f', '#ff74c0', '#c59864', 'linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%)'],
            ],
            [
                'id' => 4,
                'title' => 'Matériau',
                'content' => ['Cotton', 'Polyester', 'Rayon', 'Satin', 'Acrylic', 'Nylon', 'Velvet', 'Viscose', 'Crochet', 'Glitter', 'Lace', 'Corduroy', 'Linen', 'Metallic'],
                'show' => false,
                'defaultLimit' => 5,
                'limitBy' => 5,
            ],
        ];
    }

    public function getPriceRange(array $storeProductsData): array
    {
        $allPrices = [];

        foreach($storeProductsData as $categoryData) {
            foreach($categoryData['products'] as $productData) {
                $allPrices[] = $productData['price'];
            }
        }

        $minPrice = !empty($allPrices) ? min($allPrices) : 0;
        $maxPrice = !empty($allPrices) ? max($allPrices) : 100;

        return [
            'minPrice' => (int)$minPrice,
            'maxPrice' => (int)$maxPrice + 10,
        ];
    }
}

<?php

namespace App\Manager;

use App\Entity\Product;
use App\Entity\ProductReference;
use App\Entity\ProductVariation;

class ProductManager extends AbstractManager
{

    public function getStructuredProductData(string $reference, $basePath): ?array
    {
        $product = $this->getProductOrVariantByReference($reference);

        if(!$product) {
            return null;
        }

        $isVariant = $product instanceof ProductVariation;

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $isVariant ? $product->getPrice() : $product->getPrice(),
            'category' => $isVariant
                ? $product->getProduct()?->getCategory()?->getLabel()
                : $product->getCategory()?->getLabel(),
            'description' => $product->getDescription(),
            'sizes' => $this->getProductSizes($product),
            'images' => $this->getAllProductImages($product, $basePath),
        ];
    }

    public function getProductOrVariantByReference(string $reference): Product | ProductVariation | null
    {
        $ref = $this->entityManager->getRepository(ProductReference::class)
            ->findOneBy(['referenceCode' => $reference]);

        if(!$ref) {
            return null;
        }

        return $ref->isVariant()
            ? $ref->getProductVariation()
            : $ref->getProduct();
    }

    public function getProductSizes(Product | ProductVariation $product): array
    {
        $sizes = [];

        foreach($product->getProductSizes() as $productSize) {
            $sizes[] = $productSize->getAvailableSize()?->getAvailableSize();
        }

        return $sizes;
    }

    public function getAllProductImages(Product|ProductVariation $product,$basePath): array
    {
        $images = [];

        if ($product->getImageName()) {
            $images[] = $basePath . $product->getImageName();
        }

        foreach ($product->getAdditionalProductImages() as $image) {
            if ($image->getImageName()) {
                $images[] = $basePath . $image->getImageName();
            }
        }

        return $images;
    }

}

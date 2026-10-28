<?php

namespace App\Manager;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartManager extends AbstractManager
{

    /**
     * Adds a product (or its variation) to the cart.
     *
     * @param Product $product
     * @param int $quantity
     * @param string $size
     * @param int $productVariationId Query parameter value: 0 means no variation selected.
     */
    public function add(SessionInterface $session, Product $product, int $quantity, string $size, int $productVariationId): void
    {
        $cart = $session->get('panier', []);

        $parentId = $product->getId();
        if($productVariationId !== 0) {
            $itemType = 'variation';
        } else {
            $itemType = 'parent';
        }

        $found = false;
        foreach($cart as &$item) {
            if($item['id'] == $parentId
                && $item['size'] == $size
                && isset($item['type']) && $item['type'] === $itemType
                && ($itemType === 'parent' || ($itemType === 'variation' && $item['variationId'] == $productVariationId))
            ) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if(!$found) {
            $cart[] = [
                'id' => $parentId,
                'variationId' => $productVariationId,
                'quantity' => $quantity,
                'size' => $size,
                'type' => $itemType,
            ];
        }
        $session->set('panier', $cart);
    }

    public function getCartData(SessionInterface $session): array
    {
        $cart = $session->get('panier', []);
        $data = [];
        $total = 0;

        foreach($cart as $item) {
            $parentProduct = $this->entityManager->getRepository(Product::class)->find($item['id']);
            if(!$parentProduct) {
                continue;
            }
            $entity = $parentProduct;
            $isVariation = false;
            if(isset($item['type']) && $item['type'] === 'variation' && isset($item['variationId']) && $item['variationId'] != 0) {
                foreach($parentProduct->getProductVariations() as $variation) {
                    if($variation->getId() == $item['variationId']) {
                        $entity = $variation;
                        $isVariation = true;
                        break;
                    }
                }
            }
            $quantity = (float)$item['quantity'];
            $size = isset($item['size']) ? $item['size'] : null;
            $data[] = [
                'product' => $entity,
                'quantity' => $quantity,
                'size' => $size,
                'isVariation' => $isVariation,
            ];
            $total += $entity->getPrice() * $quantity;
        }

        return ['data' => $data, 'total' => $total];
    }

    public function remove(SessionInterface $session, Product $product, string $size): void
    {
        $cart = $session->get('panier', []);
        $parentId = $product->getId();
        foreach($cart as $key => $item) {
            if($item['id'] == $parentId && $item['size'] == $size) {
                if($item['quantity'] > 1) {
                    $cart[$key]['quantity']--;
                } else {
                    unset($cart[$key]);
                }
                break;
            }
        }
        $session->set('panier', $cart);
    }

    public function delete(SessionInterface $session, Product $product, string $size): void
    {
        $cart = $session->get('panier', []);
        $parentId = $product->getId();
        foreach($cart as $key => $item) {
            if($item['id'] == $parentId && $item['size'] == $size) {
                unset($cart[$key]);
                break;
            }
        }
        $session->set('panier', $cart);
    }
}

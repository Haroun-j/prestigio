<?php

namespace App\Controller;

use App\Entity\Product;
use App\Manager\CartManager;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, CartManager $cartManager): Response
    {
        $cartData = $cartManager->getCartData($session);
//        dd($cartData);
        return $this->render('cart/index.html.twig', $cartData);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(
        Product          $product,
        Request          $request,
        CartManager      $cartManager,
        SessionInterface $session,
        FlashyNotifier   $flashy): RedirectResponse
    {
        $source = $request->query->get('source', 'store');
        $size = $request->query->get('size', '40');
        $quantity = (int)$request->query->get('quantity', 1);
        $productVariationId = (int)$request->query->get('productVariationId', 0);

        $cartManager->add($session, $product, $quantity, $size, $productVariationId);

        $url = $this->generateUrl('cart_index');
        $flashy->success('Produit ajouté avec succès - Cliquez ici pour voir votre panier', $url);

        return $source === 'store'
            ? $this->redirectToRoute('store')
            : $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}/{size}', name: 'remove')]
    public function remove(SessionInterface $session, Product $product, Request $request, CartManager $cartManager): RedirectResponse
    {
        $size = $request->get('size');
        $cartManager->remove($session, $product, $size);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}/{size}', name: 'delete')]
    public function delete(SessionInterface $session, Product $product, Request $request, CartManager $cartManager): RedirectResponse
    {
        $size = $request->get('size');
        $cartManager->delete($session, $product, $size);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): RedirectResponse
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }

}

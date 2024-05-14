<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\CouponsRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    private EntityManagerInterface $em;
    private StripeClient $stripeClient;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->stripeClient = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em,CouponsRepository $couponsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);
        $couponCode = $session->get('couponCode');


        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('home');
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new Orders();

        // On remplit la commande
        $order->setUser($this->getUser());
        $order->setReference(uniqid());
        $order->setTransporterName('Jason Statham');
        $order->setTransporterPrice(999);
        $order->setIsPaid(false);
        $order->setMethod('VISA');
        
        if (!empty($couponCode)) {
            $coupon = $couponsRepository->findOneBy(['code' => $couponCode]);
            if ($coupon && $coupon->getUsageCount() < $coupon->getMaxUsage()) {
                $order->setCoupons($coupon);
                $coupon->setUsageCount($coupon->getUsageCount() + 1);
                $em->persist($coupon);
            } else {
                $session->remove('couponCode');
            }
        }
        
        // On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity){
            $orderDetails = new OrdersDetails();

            // On va chercher le produit
            $product = $productRepository->find($item);
            
            $price = $product->getPrice();

            // On crée le détail de commande
            $orderDetails->setProduct($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        // On persiste et on flush
        $em->persist($order);
        $em->flush();

//        $session->remove('panier');
        $session->remove('couponCode');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('app_orders_checkout');
    }
    
    /**
     * @throws ApiErrorException
     */
    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session, UrlGeneratorInterface $urlGenerator): Response
    {
        $panier = $session->get('panier', []);
        if ($panier === []) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }
        $amount = 5; /*$this->calculateTotalAmount($panier);*/
        
        $checkoutSession = $this->stripeClient->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Total commande',
                    ],
                    'unit_amount' => $amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('app_orders_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $urlGenerator->generate('app_orders_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        
        ]);
        
        return $this->redirect($checkoutSession->url);
    }
    
    /**
     * @throws ApiErrorException
     */
    #[Route('/success', name: 'success')]
    public function success(Request $request): Response
    {
        $sessionId = $request->query->get('session_id');
        $session = $this->stripeClient->checkout->sessions->retrieve($sessionId);
        $customerDetails = $session->customer_details;
        $paymentIntent = $this->stripeClient->paymentIntents->retrieve($session->payment_intent);
        $paymentMethod = $this->stripeClient->paymentMethods->retrieve($paymentIntent->payment_method);
        
        $creditCardLast4 = $paymentMethod->card->last4;
        $totalAmount =  $session->amount_total;
//dd($session->amount_total);
        return $this->render('orders/success.html.twig', [
            'customerDetails' => $customerDetails,
            'creditCardLast4' => $creditCardLast4,
            'totalAmount' => $totalAmount,
            
        ]);
    }
    
    #[Route('/cancel', name: 'cancel')]
    public function cancel(): Response
    {
        return $this->redirectToRoute('cart_index');
    }
    
}

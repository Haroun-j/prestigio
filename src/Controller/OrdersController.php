<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Entity\User;
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
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em, CouponsRepository $couponsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if($user && !$this->hasFilledAllRequiredFields($user)) {
            return $this->redirectToRoute('additional_registration_form');
        }

        if($user && !$user->isVerified()) {
            return $this->redirectToRoute('phone_verification');
        }

        $panier = $session->get('panier', []);
        $couponCode = $session->get('couponCode');

        if($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('home');
        }

        $order = new Orders();

        // On remplit la commande
        $order->setUser($this->getUser());
        $order->setReference(uniqid());
        $order->setTransporterName('Jason Statham');
        $order->setTransporterPrice(5);
        $order->setIsPaid(false);
        $order->setMethod('VISA');

        if(!empty($couponCode)) {
            $coupon = $couponsRepository->findOneBy(['code' => $couponCode]);
            if($coupon && $coupon->getUsageCount() < $coupon->getMaxUsage()) {
                $order->setCoupons($coupon);
                $coupon->setUsageCount($coupon->getUsageCount() + 1);
                $em->persist($coupon);
            } else {
                $session->remove('couponCode');
            }
        }

        foreach($panier as $item) {
            $orderDetails = new OrdersDetails();

            $parentProduct = $productRepository->find($item['id']);
            if(!$parentProduct) {
                continue;
            }

            $price = $parentProduct->getPrice();

            if(isset($item['type']) && $item['type'] === 'variation' && isset($item['variationId']) && $item['variationId'] != 0) {
                foreach($parentProduct->getProductVariations() as $variation) {
                    if($variation->getId() == $item['variationId']) {
                        $price = $variation->getPrice();
                        break;
                    }
                }
            }

            $orderDetails->setProduct($parentProduct);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($item['quantity']);
            $orderDetails->setSize($item['size']);

            $order->addOrdersDetail($orderDetails);
        }

        $em->persist($order);
        $em->flush();

//        $session->remove('panier');
        $session->remove('couponCode');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('app_orders_checkout', ['id' => $order->getId()]);
    }

    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout(int $id, SessionInterface $session, UrlGeneratorInterface $urlGenerator): Response
    {
        $order = $this->em->getRepository(Orders::class)->find($id);

        $panier = $session->get('panier', []);
        if($panier === []) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }
        $amount = $this->calculateTotalAmount($order);

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
            'success_url' => $urlGenerator->generate('app_orders_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->getId(),
            'cancel_url' => $urlGenerator->generate('app_orders_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),

        ]);

        return $this->redirect($checkoutSession->url);
    }

    private function calculateTotalAmount(Orders $order): float
    {
        $orderDetails = $order->getOrdersDetails();
        $total = 0;

        foreach($orderDetails as $details) {
            $total += $details->getPrice() * $details->getQuantity();
        }
        $coupon = $order->getCoupons();
        $discount = 0;
        $taxe = ($total * 20) / 100;
        if($coupon) {
            switch($coupon->getCouponsTypes()->getName()) {
                case 'onProduct':
                    $productFound = false;
//                                dd($details->getProduct()->getName(), $coupon->getOnProduct()->getName());

                    foreach($orderDetails as $details) {
                        if($details->getProduct()->getName() === $coupon->getOnProduct()->getName()) {
                            $productFound = true;
                            $discount += ($details->getPrice() * $details->getQuantity()) * ($coupon->getDiscount() / 100);
                        }
                    }
                    if(!$productFound) {
                        $discount = 0;
                    }
                    break;
                case 'onTaxe':
                    $taxe = 0;
                    break;
                case 'onShipping':
                    $order->setTransporterPrice(0);
                    break;
                case 'onOrder':
                    $discount = $coupon->getDiscount();
                    break;
                case 'onCategory':
                    foreach($orderDetails as $details) {
                        if($details->getProduct()->getCategory() === $coupon->getOnCategory()) {
                            $discount += ($details->getPrice() * $details->getQuantity()) * ($coupon->getDiscount() / 100);
                        }
                    }
                    break;
            }
        }
        $shippingCost = $order->getTransporterPrice();
//            dd($total,$taxe,$discount,$shippingCost);
        return $total + $taxe - $discount + $shippingCost;
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
        $orderId = $request->query->get('order_id');
        $order = $this->em->getRepository(Orders::class)->find($orderId);

        if($order) {
            $order->setIsPaid(true);
            $this->em->persist($order);
            $this->em->flush();
        }

        $creditCardLast4 = $paymentMethod->card->last4;
        $totalAmount = $session->amount_total;

        return $this->render('orders/success.html.twig', [
            'userAvatar' => $this->getUser()->getAvatar()->getAvatarName(),
            'customerDetails' => $customerDetails,
            'creditCardLast4' => $creditCardLast4,
            'totalAmount' => $totalAmount,
            '_route' => 'paymentSuccess',
        ]);
    }

    #[Route('/cancel', name: 'cancel')]
    public function cancel(): Response
    {
        return $this->redirectToRoute('cart_index');
    }

    public function hasFilledAllRequiredFields(User $user): bool
    {
        return !empty($user->getPhone());
    }
}

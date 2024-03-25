<?php
    
    namespace App\Controller;
    
    use App\Repository\CouponsRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\Routing\Annotation\Route;
    
    class CouponController extends AbstractController
    {
        #[Route('/check-coupon', name: 'check_coupon', methods: ['POST'])]
        public function checkCoupon(Request $request, CouponsRepository $couponsRepository, SessionInterface $session): JsonResponse
        {
            $couponCode = $request->request->get('couponCode');
            $coupon = $couponsRepository->findOneBy(['code' => $couponCode]);
            
            if (!$coupon) {
                return $this->json(['message' => 'Coupon non valide']);
            }
            
            if (new \DateTime() > $coupon->getValidity()) {
                return $this->json(['message' => 'Ce Coupon est expiré.']);
            }
            
            if (count($coupon->getOrders()) >= $coupon->getMaxUsage()) {
                return $this->json(['message' => 'Nombre maximal d’utilisations atteint.']);
            }
            
            if (!$coupon->getIsValid()) {
                return $this->json(['message' => 'Le coupon n’est plus valide.']);
            }
            
            $session->set('couponCode', $coupon->getCode());
            
            return $this->json([
                'message' => 'Coupon valide',
                'currency'=>$coupon->getCurrencyType(),
                'description' => $coupon->getDescription(),
                'onProduct' => $coupon->getOnProduct() ? $coupon->getOnProduct()->getName() : '',
                'onCategory' => $coupon->getOnCategory() ? $coupon->getOnCategory()->getLabel() : '',
                'validity' => $coupon->getValidity()->format('d-m-Y h:i'),
                'type' => $coupon->getCouponsTypes()->getName(),
                'discount' => $coupon->getDiscount()
            ]);
        }
    }

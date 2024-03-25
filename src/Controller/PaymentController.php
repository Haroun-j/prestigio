<?php
    namespace App\Controller;
    
    
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Attribute\Route;
    
    class PaymentController extends AbstractController{
        
        #[Route('/order/create-session-stripe',name: 'payment')]
        public function stripeCheckout(){
        
        }
    }
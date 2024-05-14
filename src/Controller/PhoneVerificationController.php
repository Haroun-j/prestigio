<?php
    
    namespace App\Controller;
    
    use App\Service\SmsGenerator;
    use Doctrine\ORM\EntityManagerInterface;
    use Exception;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\Routing\Annotation\Route;
    
    class PhoneVerificationController extends AbstractController
    {
        #[Route('/phone-verification', name: 'phone_verification')]
        public function renderTemplate (Request $request, SmsGenerator $smsGenerator, SessionInterface $session): Response
        {
            $user = $this->getUser();
            if ($user && $user->isVerified()) {
                return $this->redirectToRoute('home');
            }
            
            return $this->render('./security/phone_verification.html.twig');
        }
        
        #[Route('/send-sms', name: 'send_sms', methods: ['POST'])]
        public function sendSms (Request $request, SmsGenerator $smsGenerator, SessionInterface $session): Response
        {
            $phoneNumber = $request->request->get('phone');
            
            $phoneNumber = str_replace(' ', '', $phoneNumber);
            $phoneNumber = '+33' . substr($phoneNumber, 1);
            
            $code = rand(100000, 999999);
            
            $text = 'Pour compléter votre inscription sur notre site PRESTIOGIO, merci de saisir le code de vérification suivant :  ' . $code;
            // Store the code in the session
            $session->set('verification_code', $code);
            
            try {
                $smsGenerator->sendSms($phoneNumber, '', $text);
                
                return $this->json(['code' => $code]);
                
            } catch (Exception $e) {
                return $this->json(['message' => 'Failed to send SMS', 'error' => $e->getMessage()], 500);
            }
        }
        
        #[Route('/verify-code', name: 'verify_code_and_set_status', methods: ['POST'])]
        public function verifyCodeAndSetStatus (Request $request, EntityManagerInterface $entityManager): Response
        {
            $user = $this->getUser();
            
            if ($user && !$user->isVerified()) {
                $enteredCode = $request->request->get('code');
                $session = $request->getSession();
                $sentCode = $session->get('verification_code');
                
                if ($enteredCode == $sentCode) {
                    $user->setIsVerified(true);
                    
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    return $this->json(['message'=>'success']);
                }
                return $this->json(['message' => 'User already verified or not logged in'], 400);
            }
            return $this->json(['message' => 'Code incorrect !']);
           
        }
    }

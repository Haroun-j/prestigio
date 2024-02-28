<?php

namespace App\Controller;

use ApiPlatform\Metadata\Post;
use App\Service\SmsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
class SmsController extends AbstractController
{
    #[Route('/sendSms', name: 'send_sms', methods: ['POST'])]
    public function sendSms(Request $request, SmsGenerator $smsGenerator, SessionInterface $session): Response
    {
        $phoneNumber = $request->request->get('phone');

        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = '+33' . substr($phoneNumber, 1);

        $code = rand(100000, 999999);

        $text = 'Pour compléter votre inscription sur notre site PRESTIOGIO, merci de saisir le code de vérification suivant :  ' . $code;
        // Store the code in the session
        $session->set('verification_code', $code);

//        dd($smsGenerator->sendSms($phoneNumber, '', $text));

        try {
            $smsGenerator->sendSms($phoneNumber, '', $text);
//            return $this->json(['code' => $code]);
            return $this->json(['message' => 'success']);
        } catch (\Exception $e) {
            return $this->json(['message' => 'error']);
        }
    }

    #[Route('/verifyCodeAndSetStatus', name: 'verify_code_and_set_status', methods: ['POST'])]
    public function verifyCodeAndSetStatus(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently authenticated user
        $user = $this->getUser();

        // Check if the user is authenticated and if their status is not already verified
        if ($user && !$user->isVerified()) {
            // Assuming you have stored the verification code in the session
            $enteredCode = $request->request->get('code');
            $session = $request->getSession();
            $sentCode = $session->get('verification_code');

            // Compare the entered code with the sent code
            if ($enteredCode == $sentCode) {
                // Update user status to verified
                $user->setIsVerified(true);

                // Persist changes to the database
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect the user to a specific route upon successful verification
                return $this->redirectToRoute('home');
            }
        }

        return $this->json(['message' => 'Code incorrect !']);
    }
}

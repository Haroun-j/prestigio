<?php

namespace App\Controller;

use App\Form\UserAddressType;
use App\Form\UserGeneralInfoType;
use App\Manager\DashboardManager;
use App\Manager\UserProfileUpdateManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function index(Request $request, DashboardManager $dashboardManager, UserProfileUpdateManager $profileManager, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if(!$user) {
            throw $this->createAccessDeniedException();
        }

        $favoriteProducts = $dashboardManager->getFavoriteProductsData($user);

        // Get a fresh DTO using our new manager
        $baseDto = $profileManager->reverseTransform($user);

        // Clone it into two separate DTOs: one for general info and one for address info.
        $generalDto = clone $baseDto;
        $addressDto = clone $baseDto;

        $generalForm = $this->createForm(\App\Form\UserProfileGeneralType::class, $generalDto);
        $addressForm = $this->createForm(\App\Form\UserProfileAddressType::class, $addressDto);
        $passwordForm = $this->createForm(\App\Form\UserPasswordChangeType::class);

        $generalForm->handleRequest($request);
        $addressForm->handleRequest($request);
        $passwordForm->handleRequest($request);

        if($generalForm->isSubmitted() && $generalForm->isValid()) {
            $profileManager->transform($generalDto, $user);
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations générales ont été mises à jour.');
            return $this->redirectToRoute('app_user_dashboard');
        }

        if($addressForm->isSubmitted() && $addressForm->isValid()) {
            $profileManager->transform($addressDto, $user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse a été mise à jour.');
            return $this->redirectToRoute('app_user_dashboard');
        }

        if($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $plainPassword = $passwordForm->get('plainPassword')->getData();
            $encoder = $this->get('security.password_encoder'); // adjust for your Symfony version
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a été changé.');
            return $this->redirectToRoute('app_user_dashboard');
        }

        return $this->render('user/dashboard/index.html.twig', [
            '_route' => 'userDashboard',
            'userFullName' => $user->getFullName(),
            'userAvatar' => $user->getAvatar() ? $user->getAvatar()->getAvatarName() : null,
            'favorites' => $favoriteProducts,
            'generalForm' => $generalForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'addressForm' => $addressForm->createView(),
        ]);
    }
}

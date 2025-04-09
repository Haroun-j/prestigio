<?php

namespace App\Controller;

use App\DTO\UserAdditionalInfoData;
use App\Entity\User;
use App\Form\UserAdditionalInfoType;
use App\Manager\UserAdditionalInfoManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdditionalInfoFormController extends AbstractController
{
    #[Route('/user-infos', name: 'additional_registration_form')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserAdditionalInfoManager $userInfoManager): Response
    {
        $data = new UserAdditionalInfoData();

        $form = $this->createForm(UserAdditionalInfoType::class, $data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userIdentifier = $this->getUser()->getUserIdentifier();

            // Retrieve the actual User entity using the repository
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userIdentifier]);
            if(!$user) {
                throw $this->createNotFoundException('User not found');
            }

            //save user data
            $user = $userInfoManager->transform($data, $user);
            // persist
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été validées.');
            return $this->redirectToRoute('phone_verification');
        }

        return $this->render('registration/additional_registration_form.html.twig', [
            'form' => $form->createView(),
            '_route' => 'additionalRegistrationForm',
        ]);
    }
}

<?php
    
    namespace App\Controller;
    
    use App\Entity\Contact;
    use App\Entity\FeaturedProduct;
    use App\Form\ContactType;
    use App\Repository\UserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    
    class ContactController extends AbstractController
    {
        #[Route('/contact', name: 'contact_us')]
        public function index(
            Request $request,
            EntityManagerInterface $manager,   /*MailService $mailService*/
            UserRepository $userRepository
        ): Response {
            $contact = new Contact();
            
            $form = $this->createForm(ContactType::class, $contact);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $isClient = null !== $this->getUser();
                
                if (!$isClient) {
                    $user = $userRepository->findOneBy(['email' => $contact->getEmail()]);
                    $isClient = null !== $user;
                }
                
                if ($isClient) {
                    $user = $userRepository->findOneBy(['email' => $contact->getEmail()]);
                    if ($user) {
                        $contact->setClient($user);
                    }
                }

                $contact->setIsClient($isClient);
                
                $contact = $form->getData();
                
                $manager->persist($contact);
                $manager->flush();
                
//                //Email
//                $mailService->sendEmail(
//                    $contact->getEmail(),
//                    $contact->getSubject(),
//                    'emails/contact.html.twig',
//                    ['contact' => $contact]
//                );
                
                $this->addFlash(
                    'success',
                    'Votre demande a été envoyé avec succès !'
                );
               
                return $this->redirectToRoute('contact_us');
            } else {
                $this->addFlash(
                    'danger',
                    $form->getErrors()
                );
//                dd($form->getErrors());
            }
            
            return $this->render('contact_us/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

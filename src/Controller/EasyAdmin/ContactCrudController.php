<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\Contact;
    use Doctrine\ORM\EntityManagerInterface;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Annotation\Route;
    
    class ContactCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return Contact::class;
        }
        
        public function configureFields (string $pageName): iterable
        {
            return [
                TextField::new('fullname', 'Nom Complet')
                    ->setDisabled(),
                EmailField::new('email', 'Email'),
                TextField::new('phone', 'Numéro')
                    ->setFormType(NumberType::class)
                    ->setFormTypeOptions([
                        'attr' => [
                            'placeholder' => '0612345678',
                        ],
                    ]),
                TextField::new('subject', 'Sujet'),
                TextField::new('company', 'Entreprise')
                    ->setDisabled(),
                TextareaField::new('message', 'Message')
                    ->setDisabled(),
                DateField::new('createdAt', 'Date d\'Envoi')
                    ->setFormat('dd-MM-yyyy HH:mm')
                    ->setDisabled(),
                BooleanField::new('isClient', 'Est Client')
                    ->setDisabled(),
            ];
        }
        
        public function configureActions (Actions $actions): Actions
        {
            $goToClientAction = Action::new('goToClient', 'Aller au client', 'fa fa-user')
                ->linkToRoute('contact_go_to_client', function (Contact $contact) {
                    return ['id' => $contact->getId()];
                })
                ->setCssClass('btn btn-info')
                ->displayIf(static function ($entity) {
                    return null !== $entity->getClient();
                });
            
            $actions = parent::configureActions($actions);
            
            $actions->disable(Action::NEW);
            $actions->disable(Action::DELETE);
//            $actions->disable(Action::EDIT);
            
            $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
            $actions->add(Crud::PAGE_INDEX, $goToClientAction);
            
            return $actions;
        }
        
        #[Route('/contact/go-to-client/{id}', name: 'contact_go_to_client')]
        public function goToClient (int $id, AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $entityManager): RedirectResponse
        {
            $contact = $entityManager->getRepository(Contact::class)->find($id);
            
            if (!$contact || !$contact->getClient()) {
                throw $this->createNotFoundException('Le client n\'existe pas ou n\'est pas défini pour ce contact.');
            }
            
            $url = $adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($contact->getClient()->getId())
                ->generateUrl();
            
            return $this->redirect($url);
        }
    }

<?php

namespace App\Controller\EasyAdmin;

use App\Entity\User;
use App\Entity\UserAvatar;
use App\Form\UserAvatarType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    protected function createEntityFormBuilder($entity, $view): FormBuilderInterface
    {
        if(null === $entity->getAvatar()) {
            $entity->setAvatar(new UserAvatar());
        }
        $formBuilder = parent::createEntityFormBuilder($entity, $view);
        $formBuilder->add('avatar', UserAvatarType::class, [
            'label' => false,
            'required' => false,
        ]);
        return $formBuilder;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        // Basic fields
        $id = IdField::new('id')->onlyOnIndex();
        $avatarImage = ImageField::new('avatar.avatarName', 'Avatar')
            ->setBasePath($this->getParameter('app.path.avatar_images'))
            ->onlyOnIndex();
        $email = EmailField::new('email', 'Email');
        $firstName = TextField::new('firstName', 'First Name');
        $lastName = TextField::new('lastName', 'Last Name');
        // Although fullName isn’t persisted, the getter exists so EasyAdmin can display it.
        $fullName = TextField::new('fullName', 'Full Name')->onlyOnDetail();
        $roles = ChoiceField::new('roles', 'Roles')
            ->allowMultipleChoices()
            ->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
            ]);
        $isVerified = BooleanField::new('isVerified', 'Verified');
        $dateOfBirth = DateField::new('dateOfBirth', 'Date of Birth');
        $phone = TextField::new('phone', 'Phone');
        $gender = ChoiceField::new('gender', 'Gender')
            ->setChoices([
                'Male' => 'male',
                'Female' => 'female',
                'Other' => 'other',
            ]);
        $heroNickname = TextField::new('heroNickname', 'Nickname');

        // Address information
        $streetAddress = TextField::new('streetAddress', 'Street Address');
        $addressComplement = TextField::new('addressComplement', 'Address Complement')->hideOnIndex();
        $postalCode = TextField::new('postalCode', 'Postal Code');
        $city = TextField::new('city', 'City');
        $department = TextField::new('department', 'Department')->hideOnIndex();
        $country = TextField::new('country', 'Country');
        $newsletter = BooleanField::new('newsletter', 'Newsletter');

        // Avatar fields
        $avatarField = AssociationField::new('avatar', 'Avatar')
            ->setFormType(UserAvatarType::class)
            ->setFormTypeOption('by_reference', false)
            // Remove the extra options added automatically by the AssociationField:
            ->setFormTypeOption('class', null)
            ->setFormTypeOption('query_builder', null)
            ->onlyOnForms();


        // Configure fields per page:
        if(Crud::PAGE_INDEX === $pageName) {
            return [
                $id,
                $avatarImage,
                $email,
                $firstName,
                $lastName,
                $isVerified,
                $phone,
                $city,
            ];
        } elseif(Crud::PAGE_DETAIL === $pageName) {
            return [
                $id,
                $avatarImage,
                $email,
                $firstName,
                $lastName,
                $fullName,
                $roles,
                $isVerified,
                $dateOfBirth,
                $phone,
                $gender,
                $heroNickname,
                $streetAddress,
                $addressComplement,
                $postalCode,
                $city,
                $department,
                $country,
                $newsletter,
            ];
        } elseif(Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [
                // Tab 1: Basic Information
                FormField::addTab('Basic Information'),
                $email,
                $firstName,
                $lastName,
                $roles,
                $isVerified,
                $dateOfBirth,
                $phone,
                $gender,
                $heroNickname,

                // Tab 2: Address Information
                FormField::addTab('Address Information'),
                $streetAddress,
                $addressComplement,
                $postalCode,
                $city,
                $department,
                $country,
                $newsletter,

                // Tab 3: Avatar
                FormField::addTab('Avatar'),
                $avatarField,
            ];
        }

        return [];
    }
}

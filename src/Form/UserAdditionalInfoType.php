<?php

namespace App\Form;

use App\DTO\UserAdditionalInfoData;
use App\Form\Type\CivilityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdditionalInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Informations personnelles
            ->add('firstName', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
                'required' => true,
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date de Naissance *',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de Téléphone *',
                'required' => true,
            ])
            ->add('gender', CivilityType::class, [
                'label' => 'Genre *',
                'placeholder' => 'Sélectionnez un genre',
                'required' => true,
            ])
            ->add('heroNickname', TextType::class, [
                'label' => 'Surnom de Super-Héros (optionnel)',
                'required' => false,
            ])

            // Adresse
            ->add('streetAddress', TextType::class, [
                'label' => 'Adresse (Numéro et Rue) *',
                'required' => true,
            ])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complément d\'adresse (optionnel)',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal *',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'required' => true,
            ])
            ->add('department', TextType::class, [
                'label' => 'Département (optionnel)',
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays *',
                'data' => 'France',
                'required' => true,
            ])

            // Vérification des informations et Newsletter
            ->add('confirmData', CheckboxType::class, [
                'label' => 'Je confirme avoir vérifié et lu attentivement mes informations *',
                'required' => true,
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'S\'abonner à la newsletter',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAdditionalInfoData::class,
        ]);
    }
}

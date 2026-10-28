<?php
// src/Form/UserProfileGeneralType.php
namespace App\Form;

use App\DTO\UserProfileUpdateData;
use App\Form\Type\CivilityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileGeneralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom *',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date de naissance *',
                'widget' => 'single_text',
            ])
            ->add('gender', CivilityType::class, [
                'label' => 'Genre *',
                'required' => false,
                'placeholder' => 'Sélectionnez un genre',
            ])
            ->add('heroNickname', TextType::class, [
                'label'    => 'Surnom de super-héros (optionnel)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfileUpdateData::class,
        ]);
    }
}

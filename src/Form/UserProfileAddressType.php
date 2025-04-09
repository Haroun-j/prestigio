<?php
// src/Form/UserProfileAddressType.php
namespace App\Form;

use App\DTO\UserProfileUpdateData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('streetAddress', TextType::class, [
                'label' => 'Adresse (Numéro et Rue) *',
            ])
            ->add('addressComplement', TextType::class, [
                'label'    => 'Complément d\'adresse (optionnel)',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal *',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
            ])
            ->add('department', TextType::class, [
                'label'    => 'Département (optionnel)',
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays *',
                'data'  => 'France',
            ])
            ->add('newsletter', null, [
                'label'    => 'S\'abonner à la newsletter',
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

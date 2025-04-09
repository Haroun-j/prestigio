<?php
// src/Form/UserPasswordChangeType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Note: these fields are "mapped" as false so you can handle updating the password
        $builder->add('plainPassword', RepeatedType::class, [
            'type'            => PasswordType::class,
            'mapped'          => false,
            'first_options'   => ['label' => 'Nouveau mot de passe'],
            'second_options'  => ['label' => 'Confirmer le mot de passe'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}

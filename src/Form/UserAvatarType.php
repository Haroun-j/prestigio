<?php
// src/Form/UserAvatarType.php
namespace App\Form;

use App\Entity\UserAvatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', VichImageType::class, [
            'label' => 'Upload Avatar',
            'required' => false,
            'allow_delete' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Set the data_class for your UserAvatar entity
        $resolver->setDefaults([
            'data_class' => UserAvatar::class,
        ]);

        // Define the unwanted options so they are accepted when passed, but only as null.
        $resolver->setDefined(['class', 'query_builder']);
        $resolver->setAllowedTypes('class', ['null']);
        $resolver->setAllowedTypes('query_builder', ['null']);
    }
}

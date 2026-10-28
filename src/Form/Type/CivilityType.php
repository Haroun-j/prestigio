<?php
// src/Form/Type/CivilityType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CivilityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'Monsieur' => 'monsieur',
                'Madame' => 'madame',
                'Autre' => 'autre',
            ],
            'placeholder' => 'Sélectionnez une civilité',
            'required' => true,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                    'placeholder' => 'Votre Nom Complet'
                ],
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'col-form-label',
                ]
            ])
            ->add('email', EmailType::class, [
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                    'placeholder' => 'Votre Adresse Mail'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '^\d{10}$',
                    'placeholder' => 'Ex: 0612345678',
                    'inputmode' => 'numeric',
                    'maxlength' => 10,
                    
                ],
                'label' => 'Numéro',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ]),
                ]
            ])
            ->add('company', TextType::class, [
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                    'placeholder' => 'Votre Nom d\'Entreprise'
                ],
                'label' => 'Entreprise',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
            ])
            ->add('subject', TextType::class, [
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                    'placeholder' => 'Écrivez le sujet de votre demande '
                ],
                'label' => 'Sujet',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add('message', TextareaType::class, [
                'required'=> true,
                'attr' => [
                    'class' => 'form-control',
                    'cols' => '30',
                    'rows' => '4',
                    'placeholder' => 'Écrivez Votre message'
                ],
                'label' => 'Message *',
                'label_attr' => [
                    'class' => 'col-form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary rounded-0 py-2 px-4'
                ],
                'label' => 'Envoyer'
            ]);
/*            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
                'action_name' => 'contact',
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

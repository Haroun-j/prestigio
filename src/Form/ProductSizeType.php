<?php
    
    namespace App\Form;
    
    use App\Entity\AvailableSize;
    use App\Entity\ProductSize;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    
    class ProductSizeType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('availableSize', EntityType::class, [
                    'class' => AvailableSize::class,
                    'choice_label' => 'availableSize',
                ])
                ->add('quantity', NumberType::class);
        }
        
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ProductSize::class,
            ]);
        }
    }

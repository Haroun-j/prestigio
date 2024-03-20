<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\AvailableSize;
    use App\Entity\ProductSize;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    class ProductSizesCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return ProductSize::class;
        }
        
        public function configureFields(string $pageName): iterable
        {
            return [
                AssociationField::new('product', 'Product')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                AssociationField::new('productVariation', 'Product Variation')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                AssociationField::new('size', 'Size')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                NumberField::new('quantity', 'Quantity'),
            ];
        }
    }

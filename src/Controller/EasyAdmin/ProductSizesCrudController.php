<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\ProductSize;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
                AssociationField::new('product', 'Produit')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                AssociationField::new('productVariation', 'Variation')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                AssociationField::new('size', 'Taille')
                    ->setFormTypeOptions([
                        'by_reference' => true,
                    ]),
                NumberField::new('quantity', 'Quantité'),
            ];
        }
        public function configureActions(Actions $actions): Actions
        {
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
        }
    }

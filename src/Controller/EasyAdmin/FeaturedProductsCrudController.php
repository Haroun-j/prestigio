<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\FeaturedProduct;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    
    class FeaturedProductsCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return FeaturedProduct::class;
        }
        
        public function configureFields (string $pageName): iterable
        {
            $fields = [
                IdField::new('id')->hideOnForm(),
                AssociationField::new('product', 'Produit')
                    ->setFormTypeOptions([
                        'choice_label' => 'name',
                    ])
                ->onlyOnIndex(),
                NumberField::new('displayOrder', 'Ordre d\'affichage')
                ->onlyOnIndex(),
            ];
            
            return $fields;
        }
        
        public function configureActions (Actions $actions): Actions
        {
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
        }
    }

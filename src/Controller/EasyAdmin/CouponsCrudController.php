<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\Category;
    use App\Entity\Coupons;
    use App\Entity\Orders;
    use App\Entity\Product;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    
    class CouponsCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn(): string
        {
            return Coupons::class;
        }
        
        public function configureFields(string $pageName): iterable
        {
            $productField = AssociationField::new('onProduct', 'Sur Produit')
                ->setFormTypeOptions(['class' => Product::class, 'choice_label' => 'name'])
                ->hideOnIndex()
                ->setHelp('Sélectionnez un produit seulement si le type de coupon est "Sur Produit".');
            
            $categoryField = AssociationField::new('onCategory', 'Sur Catégorie')
                ->setFormTypeOptions(['class' => Category::class, 'choice_label' => 'label'])
                ->hideOnIndex()
                ->setHelp('Sélectionnez une catégorie seulement si le type de coupon est "Sur Catégorie".');
            
            if (Crud::PAGE_EDIT === $pageName && $this->getContext()->getEntity()->getInstance()) {
                $couponType = $this->getContext()->getEntity()->getInstance()->getCouponsTypes()->getName();
                
                if ('onProduct' === $couponType) {
                    $categoryField->setFormTypeOption('disabled', true);
                } elseif ('onCategory' === $couponType) {
                    $productField->setFormTypeOption('disabled', true);
                } else {
                    $productField->setFormTypeOption('disabled', true);
                    $categoryField->setFormTypeOption('disabled', true);
                }
            }
            return [
                NumberField::new('id', 'ID')->onlyOnIndex(),
                TextField::new('code', 'Code'),
                TextField::new('description', 'Description'),
                NumberField::new('discount', 'Montant'),
                $productField,
                $categoryField,
                NumberField::new('max_usage', 'Maximum D\'utilisation'),
                DateField::new('validity', 'Validité'),
                BooleanField::new('is_valid', 'Est valide ?'),
                AssociationField::new('coupons_types', 'Type de Coupon'),
                AssociationField::new('orders', 'Commandes Associées')->hideOnIndex(),
            ];
        }

        public function configureActions(Actions $actions): Actions
        {
            return $actions
//                ->disable(Action::NEW, Action::EDIT)
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
        }
    }

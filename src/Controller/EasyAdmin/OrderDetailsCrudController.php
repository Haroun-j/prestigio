<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\OrdersDetails;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    
    class OrderDetailsCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return OrdersDetails::class;
        }
        
        public function configureFields (string $pageName): iterable
        {
            return [
                IdField::new('id'),
                TextField::new('product.name', 'Product'),
                NumberField::new('quantity'),
                NumberField::new('size'),
                NumberField::new('price'),
            ];
        }
    }
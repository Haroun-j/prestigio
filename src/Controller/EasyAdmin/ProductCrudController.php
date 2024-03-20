<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\Product;
    use App\Form\ProductSizeType;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    use Vich\UploaderBundle\Form\Type\VichImageType;
    
    class ProductCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return Product::class;
        }
        
        public function configureFields (string $pageName): iterable
        {
            return [
                TextField::new('name'),
                ImageField::new('imageName', 'Product Image')
                    ->setBasePath($this->getParameter('app.path.product_images'))
                    ->onlyOnIndex(),
                AssociationField::new('category'),
                NumberField::new('price'),
                NumberField::new('availableStock', 'Stock')
                    ->onlyOnIndex()
                    ->formatValue(function ($value, Product $product) {
                        return $product->calculateAvailableStock();
                    })->onlyOnIndex(),
                CollectionField::new('productSizes')
                    ->setEntryType(ProductSizeType::class)
                    ->allowAdd()
                    ->allowDelete(),
                TextField::new('color')
                    ->hideOnIndex(),
                NumberField::new('Weight')
                    ->hideOnIndex(),
                textField::new('CountryOfOrigin')
                    ->hideOnIndex(),
                DateField::new('DateOfManufacture')
                    ->hideOnIndex(),
                TextField::new('description'),
                TextareaField::new('imageFile', 'Product Image')
                    ->hideOnIndex()
                    ->setFormType(VichImageType::class)
                    ->setFormTypeOption('allow_delete', false),
                BooleanField::new('status'),
                CollectionField::new('productVariations', 'Variations')
                    ->useEntryCrudForm(ProductVariationCrudController::class)
                    ->allowDelete(true)
                    ->allowAdd(true),
            ];
        }
    }

<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\Product;
    use App\Entity\ProductVariation;
    use App\Form\ProductSizeType;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    use Vich\UploaderBundle\Form\Type\VichImageType;
    

    class ProductVariationCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn (): string
        {
            return ProductVariation::class;
        }
        
        public function configureFields (string $pageName): iterable
        {
            return [
                ImageField::new('imageName', 'Product Image')
                    ->setBasePath($this->getParameter('app.path.product_variation_images'))
                    ->onlyOnIndex(),
                AssociationField::new('product'),
                CollectionField::new('productSizes')
                    ->setEntryType(ProductSizeType::class)
                    ->allowAdd()
                    ->allowDelete(),
                TextField::new('color', 'Color'),
                NumberField::new('Weight', 'Weight'),
                TextField::new('CountryOfOrigin', 'Country of Origin'),
                DateTimeField::new('DateOfManufacture', 'Date of Manufacture')
                    ->setFormat('yyyy-MM-dd HH:mm:ss'),
                IntegerField::new('availableStock', 'Stock')
                    ->setVirtual(true)
                    ->formatValue(function ($value, ?ProductVariation $productVariation) {
                        if ($productVariation) {
                            return $productVariation->calculateAvailableStock();
                        }
                        return 0;
                    })->onlyOnIndex(),
                NumberField::new('AdditionalPrice', 'Additional Price'),
                TextareaField::new('imageFile', 'Product Image')
                    ->setFormType(VichImageType::class)
                    ->hideOnIndex()
                    ->setFormTypeOption('allow_delete', false),
            
            ];
        }
    }

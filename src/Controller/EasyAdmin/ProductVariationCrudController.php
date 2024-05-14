<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\ProductVariation;
    use App\Form\ProductSizeType;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
                ImageField::new('imageName', 'Image')
                    ->setBasePath($this->getParameter('app.path.product_variation_images'))
                    ->onlyOnIndex(),
                AssociationField::new('product','Produit Parent'),
                CollectionField::new('productSizes','Tailles')
                    ->setEntryType(ProductSizeType::class)
                    ->allowAdd()
                    ->allowDelete(),
                TextField::new('color', 'Couleur'),
                TextField::new('colorCode', 'Code Couleur'),
                NumberField::new('Weight', 'Poids ( g )'),
                TextField::new('CountryOfOrigin', 'Pays d\Origine'),
                DateTimeField::new('DateOfManufacture', 'Date De Fabrication')
                    ->setFormat('yyyy-MM-dd HH:mm:ss'),
                IntegerField::new('availableStock', 'Stock')
                    ->setVirtual(true)
                    ->formatValue(function ($value, ?ProductVariation $productVariation) {
                        if ($productVariation) {
                            return $productVariation->calculateAvailableStock();
                        }
                        return 0;
                    })->onlyOnIndex(),
                NumberField::new('AdditionalPrice', 'Prix'),
                TextareaField::new('imageFile', 'Image Uploader')
                    ->setFormType(VichImageType::class)
                    ->hideOnIndex()
                    ->setFormTypeOption('allow_delete', false),
            
            ];
        }
        public function configureActions(Actions $actions): Actions
        {
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
        }
    }

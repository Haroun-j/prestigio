<?php
    
    namespace App\Controller\EasyAdmin;
    
    use App\Entity\Product;
    use App\Form\ProductSizeType;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
                TextField::new('name','Nom'),
                ImageField::new('imageName', 'Image')
                    ->setBasePath($this->getParameter('app.path.product_images'))
                    ->onlyOnIndex(),
                AssociationField::new('category','Categorie'),
                NumberField::new('price','Prix'),
                NumberField::new('availableStock', 'Stock')
                    ->onlyOnIndex()
                    ->formatValue(function ($value, Product $product) {
                        return $product->calculateAvailableStock();
                    })->onlyOnIndex(),
                CollectionField::new('productSizes','Tailles')
                    ->setEntryType(ProductSizeType::class)
                    ->allowAdd()
                    ->allowDelete(),
                TextField::new('color','Couleur')
                    ->hideOnIndex(),
                NumberField::new('Weight','Poids ( g )')
                    ->hideOnIndex(),
                textField::new('CountryOfOrigin','Pays d\'Origine')
                    ->hideOnIndex(),
                DateField::new('DateOfManufacture','Date De Fabrication')
                    ->hideOnIndex(),
                TextField::new('description','Description'),
                TextareaField::new('imageFile', 'Image Uploader')
                    ->hideOnIndex()
                    ->setFormType(VichImageType::class)
                    ->setFormTypeOption('allow_delete', false),
                BooleanField::new('status','Status'),
                CollectionField::new('productVariations', 'Variations')
                    ->useEntryCrudForm(ProductVariationCrudController::class)
                    ->allowDelete(true)
                    ->allowAdd(true),
            ];
        }
        public function configureActions(Actions $actions): Actions
        {
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
        }
    }

<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','ID')
            ->onlyOnIndex(),
            TextField::new('label','Label'),
            CollectionField::new('products', 'Produits associées')
                ->useEntryCrudForm(ProductCrudController::class)
                ->allowDelete(true)
                ->allowAdd(true),
        ];
    }
}

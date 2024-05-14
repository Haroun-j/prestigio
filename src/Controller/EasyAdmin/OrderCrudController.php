<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Orders;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Orders::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            NumberField::new('id')
            ->onlyOnIndex(),
            TextField::new('reference','Réference'),
            AssociationField::new('user','Utilisateur'),
            DateTimeField::new('createdAt','Date de création'),
            AssociationField::new('coupons','Coupons'),
        ];
        
        if (Crud::PAGE_DETAIL === $pageName) {
            $fields[] = TextField::new('orderDetailsSummary', 'Détails Commande')
                ->formatValue(function ($value, $entity) {
                    return $entity->getOrderDetailsSummary();
                });
        } else {
            $fields[] = AssociationField::new('ordersDetails', 'Détails Commande')
                ->setCrudController(OrderDetailsCrudController::class);
        }
        return $fields;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
//            ->disable(Action::NEW, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}

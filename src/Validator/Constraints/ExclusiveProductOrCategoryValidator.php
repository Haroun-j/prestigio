<?php
    // src/Validator/Constraints/ExclusiveProductOrCategoryValidator.php
    
    namespace App\Validator\Constraints;
    
    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;
    use App\Entity\Coupons;
    
    class ExclusiveProductOrCategoryValidator extends ConstraintValidator
    {
        public function validate($coupon, Constraint $constraint)
        {
            /* @var $coupon Coupons */
            /* @var $constraint ExclusiveProductOrCategory */
            
            $couponType = $coupon->getCouponsTypes() ? $coupon->getCouponsTypes()->getName() : '';
            
            switch ($couponType) {
                case 'onOrder':
                    if ($coupon->getOnProduct() || $coupon->getOnCategory()) {
                        $this->context->buildViolation('Pour un coupon de type "Sur Commande", aucun produit ou catégorie ne doit être sélectionné.')
                            ->addViolation();
                    }
                    break;
                case 'onCategory':
                    if (!$coupon->getOnCategory() || $coupon->getOnProduct()) {
                        $this->context->buildViolation('Pour un coupon de type "Sur Catégorie", une catégorie doit être sélectionnée et aucun produit ne doit être sélectionné.')
                            ->addViolation();
                    }
                    break;
                case 'onProduct':
                    if (!$coupon->getOnProduct() || $coupon->getOnCategory()) {
                        $this->context->buildViolation('Pour un coupon de type "Sur Produit", un produit doit être sélectionné et aucune catégorie ne doit être sélectionnée.')
                            ->addViolation();
                    }
                    break;
                default:
                    // Pour les autres types de coupons, assurez-vous qu'aucun produit ou catégorie n'est sélectionné.
                    if ($coupon->getOnProduct() || $coupon->getOnCategory()) {
                        $this->context->buildViolation('Pour ce type de coupon, aucun produit ou catégorie ne doit être sélectionné.')
                            ->addViolation();
                    }
                    break;
            }
        }
    }

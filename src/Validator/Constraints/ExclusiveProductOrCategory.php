<?php
    
    namespace App\Validator\Constraints;
    
    use Symfony\Component\Validator\Constraint;
    
    /**
     * @Annotation
     */
    #[\Attribute] class ExclusiveProductOrCategory extends Constraint
    {
        public $message = 'Un coupon ne peux pas s\'associer avec un produuit et une categorie à la fois.';
        
        public function getTargets()
        {
            return self::CLASS_CONSTRAINT;
        }
    }

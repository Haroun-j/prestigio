<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Entity\ProductReference;
use App\Entity\ProductVariation;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Uid\Uuid;

class ProductReferenceSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::postPersist];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();

        if($entity instanceof Product || $entity instanceof ProductVariation) {
            $existing = $entity->getProductReference();
            if(!$existing) {
                $ref = new ProductReference();
                $ref->setReferenceCode('RMA' . strtoupper(substr(Uuid::v4()->toRfc4122(), 0, 13)));
                $ref->setIsVariant($entity instanceof ProductVariation);

                if($entity instanceof Product) {
                    $ref->setProduct($entity);
                    $entity->setProductReference($ref);
                } else {
                    $ref->setProductVariation($entity);
                    $entity->setProductReference($ref);
                }

                $em->persist($ref);
                $em->flush();
            }
        }
    }
}

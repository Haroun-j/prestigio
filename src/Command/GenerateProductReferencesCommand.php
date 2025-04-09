<?php

// src/Command/GenerateProductReferencesCommand.php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductReference;
use App\Entity\ProductVariation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'app:generate-product-references')]
class GenerateProductReferencesCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $variations = $this->em->getRepository(ProductVariation::class)->findAll();

        foreach(array_merge($products, $variations) as $entity) {
            if(!$entity->getProductReference()) {
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

                $this->em->persist($ref);
            }
        }

        $this->em->flush();
        $output->writeln('<info>Product references generated successfully.</info>');

        return Command::SUCCESS;
    }
}

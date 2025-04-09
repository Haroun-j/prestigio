<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AbstractManager
{
    protected EntityManagerInterface $entityManager;
    protected ParameterBagInterface $params;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
    }
}

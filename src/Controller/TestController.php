<?php

// Assuming you have a Symfony project with Twig configured

// YourController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function yourAction(): Response
    {
        $card = [
            'product_name' => 'Your Product',
            'product_card_colors' => ['#ff0000', '#00ff00'],
            'thumbnail' => '/path/to/thumbnail.jpg',
            'stars' => 3,
            'sizes' => ['S', 'M', 'L'],
            'colors' => ['#ff0000', '#00ff00', '#0000ff'],
            'price' => 50,
            'unit' => 'pcs',
            'isNew' => true,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        return $this->render('test/test.html.twig', [
            'card' => $card,
        ]);
    }
}

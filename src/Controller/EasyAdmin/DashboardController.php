<?php

namespace App\Controller\EasyAdmin;

use App\Entity\AvailableSize;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Coupons;
use App\Entity\FeaturedProduct;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\ProductSize;
use App\Entity\ProductVariation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();
        return $this->render('EasyAdmin/Dashboard.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Prestigio');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-table-cells', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-store', Product::class);
        yield MenuItem::linkToCrud('Variations des produits', 'fa-solid fa-palette', ProductVariation::class);
        yield MenuItem::linkToCrud('Tailles Dispo', 'fa-solid fa-maximize', ProductSize::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-dolly', Orders::class);
        yield MenuItem::linkToCrud('Coupons', 'fa-solid fa-money-bill', Coupons::class);
        yield MenuItem::linkToCrud('Contact', 'fa-regular fa-comments', Contact::class);
        yield MenuItem::linkToCrud('Produits en tendance', 'fa-solid fa-medal', FeaturedProduct::class);
    }
}

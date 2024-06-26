<?php

namespace App\Infrastructure\Controllers\Admin;

use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Subscriptions;

use App\Infrastructure\Database\Entity\News;
use App\Infrastructure\Database\Entity\Users;
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
        return $this->render('Admin/admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Entities');
//        yield MenuItem::linkToCrud('Currencies', 'fas fa-coins', Currencies::class);
        //        yield MenuItem::linkToCrud('Subscriptions', 'fas fa-bell', Subscriptions::class);

        yield MenuItem::linkToCrud('News', 'fas fa-newspaper', News::class);
        yield MenuItem::section('Users Management');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', Users::class);
    }
}
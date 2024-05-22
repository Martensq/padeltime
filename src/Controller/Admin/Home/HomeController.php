<?php

namespace App\Controller\Admin\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/admin/home/home', name: 'app_admin_home_home')]
    public function index(): Response
    {
        return $this->render('admin/home/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

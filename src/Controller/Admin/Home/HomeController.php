<?php

namespace App\Controller\Admin\Home;

use App\Repository\ContactRepository;
use App\Repository\CourtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'admin_home', methods: ['GET'])]
    public function index(
        CourtRepository $courtRepository,
        ContactRepository $contactRepository

    ): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            "courts" => $courtRepository->findAll(),
            "contacts" => $contactRepository->findAll()
        ]);
    }
}

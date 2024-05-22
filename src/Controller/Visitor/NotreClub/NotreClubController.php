<?php

namespace App\Controller\Visitor\NotreClub;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotreClubController extends AbstractController
{
    #[Route('/notre-club', name: 'visitor_notreClub_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/notre_club/index.html.twig');
    }
}

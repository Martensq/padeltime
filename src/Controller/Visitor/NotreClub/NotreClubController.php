<?php

namespace App\Controller\Visitor\NotreClub;

use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotreClubController extends AbstractController
{
    #[Route('/notre-club', name: 'visitor_notreClub_index', methods:['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        return $this->render('pages/visitor/notre_club/index.html.twig', [
            "setting" => $settingRepository->find(4)
        ]);
    }
}

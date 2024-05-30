<?php

namespace App\Controller\Visitor\LePadel;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LePadelController extends AbstractController
{
    #[Route('/le-padel', name: 'visitor_lePadel_index', methods: ['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        return $this->render('pages/visitor/le_padel/index.html.twig', [
            'setting' => $settingRepository->find(1)
        ]);
    }
}

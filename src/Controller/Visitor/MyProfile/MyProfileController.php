<?php

namespace App\Controller\Visitor\MyProfile;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyProfileController extends AbstractController
{
    #[Route('/my-profile', name: 'visitor_myProfile_index')]
    public function index(SettingRepository $settingRepository): Response
    {
        return $this->render('pages/visitor/my_profile/index.html.twig', [
            'setting' => $settingRepository->find(4)
        ]);
    }
}

<?php

namespace App\Controller\Admin\Setting;

use DateTimeImmutable;
use App\Entity\Setting;
use App\Form\SettingFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingController extends AbstractController
{
    #[Route('/admin/setting', name: 'admin_setting_index', methods:['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        $setting = $settingRepository->find(1);

        return $this->render('pages/admin/setting/index.html.twig', [
            'setting' => $setting,
        ]);
    }

    #[Route('/admin/setting/{id<\d+>}/edit', name: 'admin_setting_edit', methods:['GET', 'POST'])]
    public function edit(Setting $setting, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SettingFormType::class, $setting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $setting->setUpdatedAt(new DateTimeImmutable());

            $em->persist($setting);
            $em->flush();

            $this->addFlash("success", "Les paramètres ont été modifiés");

            return $this->redirectToRoute('admin_setting_index');
        }
        
        return $this->render('pages/admin/setting/edit.html.twig', [
            'form' =>$form->createView()
        ]);
    }

}

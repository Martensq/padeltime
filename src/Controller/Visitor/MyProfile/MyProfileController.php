<?php

namespace App\Controller\Visitor\MyProfile;

use App\Entity\User;
use DateTimeImmutable;
use App\Form\EditMyProfileFormType;
use App\Form\EditPasswordFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class MyProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SettingRepository $settingRepository
    )
    {
    }

    #[Route('/my-profile', name: 'visitor_myProfile_index')]
    public function index(): Response
    {
        return $this->render('pages/visitor/my_profile/index.html.twig', [
            'setting' => $this->settingRepository->find(4)
        ]);
    }

    #[Route('/my-profile/edit', name: 'visitor_myProfile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request): Response
    {    
        $user = $this->getUser();

        $form = $this->createForm(EditMyProfileFormType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash("success", "Les informations du profil ont été modifiées");

            return $this->redirectToRoute("visitor_myProfile_index");
        }

        return $this->render("pages/visitor/my_profile/edit_profile.html.twig", [
            'form' => $form->createView(),
            'setting' => $this->settingRepository->find(4)
        ]);
    }

    #[Route('/my-profile/edit/password', name: 'visitor_myProfile_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditPasswordFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $plainPassword = $form->getData()['plainPassword'];

            $passwordHashed = $hasher->hashPassword($user, $plainPassword);

            $user->setPassword($passwordHashed);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Le mot de passe a été modifié.');

            return $this->redirectToRoute('visitor_myProfile_index');            
        }

        return $this->render("pages/visitor/my_profile/edit_password.html.twig", [
            'form' => $form->createView(),
            'setting' => $this->settingRepository->find(4)
        ]);
    }

    #[Route('/my-profile/delete}', name: 'visitor_myProfile_delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_profile', $request->request->get('_csrf_token')))
        {
            $user = $this->getUser();
            $this->addFlash('success', "Votre compte a été supprimé");

            $this->container->get('security.token_storage')->setToken(null);
            
            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute("visitor_myProfile_index");
    }

}

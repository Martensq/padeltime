<?php

namespace App\Controller\User\MyProfile;


use App\Form\EditPasswordFormType;
use App\Form\EditMyProfileFormType;
use App\Repository\BookingRepository;
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
        private SettingRepository $settingRepository,
        private BookingRepository $bookingRepository
    )
    {
    }

    #[Route('/mon-profil', name: 'user_myProfile_index')]
    public function index(): Response
    {
        $bookings = $this->bookingRepository->findFutureBookingsByUser($this->getUser());

        return $this->render('pages/user/my_profile/index.html.twig', [
            'setting' => $this->settingRepository->find(4),
            'bookings' => $bookings
        ]);
    }

    #[Route('/mon-profil/modifier', name: 'user_myProfile_edit', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute("user_myProfile_index");
        }

        return $this->render("pages/user/my_profile/edit_profile.html.twig", [
            'form' => $form->createView(),
            'setting' => $this->settingRepository->find(4)
        ]);
    }

    #[Route('/mon-profil/modifier/mot-de-passe', name: 'user_myProfile_edit_password', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('user_myProfile_index');            
        }

        return $this->render("pages/user/my_profile/edit_password.html.twig", [
            'form' => $form->createView(),
            'setting' => $this->settingRepository->find(4)
        ]);
    }

    #[Route('/mon-profil/supprimer}', name: 'user_myProfile_delete', methods: ['POST'])]
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

        return $this->redirectToRoute("user_myProfile_index");
    }

}

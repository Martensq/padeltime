<?php

namespace App\Controller\Visitor\Authentication;


use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/connexion', name: 'visitor_authentication_login', methods:['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, SettingRepository $settingRepository): Response
    {
        // Si l'utilisateur est déjà connecté
            // il n'a plus rien à faire sur le page de connexion
            // redirection vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('visitor_home_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // 1- Afficher la page de connexion
        return $this->render('pages/visitor/authentication/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error,
            'setting' => $settingRepository->find(1)
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

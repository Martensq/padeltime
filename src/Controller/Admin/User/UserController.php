<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use DateTimeImmutable;
use App\Repository\UserRepository;
use App\Form\EditUserRolesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/user/list', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('pages/admin/user/index.html.twig', [
            "users" => $users
        ]);
    }

    #[Route('/user/{id<\d+>}/edit/roles', name: 'admin_user_edit_roles', methods: ['GET', 'POST'])]
    public function editRoles(User $user, Request $request, EntityManagerInterface $em): Response
    {    
        $form = $this->createForm(EditUserRolesFormType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setUpdatedAt(new DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Les rôles de {$user->getFirstName()} {$user->getLastName()} ont été modifié");

            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render("pages/admin/user/edit_roles.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/{id<\d+>}/delete}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_user_'.$user->getId(), $request->request->get('_csrf_token')))
        {
            $this->addFlash('success', "L'utilisateur' {$user->getFirstName()} {$user->getLastName()} a été supprimé");
            
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute("admin_user_index");
    }

}

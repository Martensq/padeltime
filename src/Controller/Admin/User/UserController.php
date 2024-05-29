<?php

namespace App\Controller\Admin\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class UserController extends AbstractController
{
    
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    )
    {
    }

    #[Route('/user/list', name: 'admin_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/user/index.html.twig', [
            "users" => $this->userRepository->findAll()
        ]);
    }

    // #[Route('/user/{id<\d+>}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    // public function edit(User $user, Request $request): Response
    // {    
    //     $form = $this->createForm(AdminUserFormType::class, $user);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $user->setUpdatedAt(new DateTimeImmutable());

    //         $this->em->persist($user);
    //         $this->em->flush();

    //         $this->addFlash("success", "Le numéro du user a été modifié");

    //         return $this->redirectToRoute("admin_user_index");
    //     }

    //     return $this->render("pages/admin/user/edit.html.twig", [
    //         'user' => $user,
    //         'form' => $form->createView()
    //     ]);
    // }

    // #[Route('/court/{id<\d+>}/delete}', name: 'admin_court_delete', methods: ['POST'])]
    // public function delete(Court $court, Request $request): Response
    // {
    //     if ($this->isCsrfTokenValid('delete_court_'.$court->getId(), $request->request->get('_csrf_token')))
    //     {
    //         $this->addFlash('success', "La piste {$court->getCourtNumber()} a été supprimée");
            
    //         $this->em->remove($court);
    //         $this->em->flush();
    //     }

    //     return $this->redirectToRoute("admin_court_index");
    // }

}

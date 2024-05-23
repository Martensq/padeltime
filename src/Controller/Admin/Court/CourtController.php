<?php

namespace App\Controller\Admin\Court;


use DateTimeImmutable;
use App\Repository\CourtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CourtController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private CourtRepository $courtRepository
    )
    {
    }

    #[Route('/court/list', name: 'admin_court_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/court/index.html.twig', [
            "courts" => $this->courtRepository->findAll()
        ]);
    }


    // #[Route('/court/create', name: 'admin_court_create', methods: ['GET', 'POST'])]
    // public function create(Request $request): Response
    // {
    //     $category = new Category();
        
    //     $form = $this->createForm(AdminCategoryFormType::class, $category);
        
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $category->setCreatedAt(new DateTimeImmutable());
    //         $category->setUpdatedAt(new DateTimeImmutable());

    //         $this->em->persist($category);

    //         $this->em->flush();
            
    //         $this->addFlash('success', "La catégorie {$category->getName()} a été ajoutée avec succès.");

    //         return $this->redirectToRoute('admin_category_index');
    //     }

    //     return $this->render('pages/admin/category/create.html.twig', [
    //         "form" => $form->createView()
    //     ]);
    // }

    // #[Route('/category/{id<\d+>}/edit}', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    // public function edit(Category $category, Request $request): Response
    // {
    //     $form = $this->createForm(AdminCategoryFormType::class, $category);
        
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $category->setUpdatedAt(new DateTimeImmutable());

    //         $this->em->persist($category);
    //         $this->em->flush();

    //         $this->addFlash('success', "La catégorie {$category->getName()} a été modifée");
    //         return $this->redirectToRoute('admin_category_index');
    //     }

    //     return $this->render("pages/admin/category/edit.html.twig", [
    //         "form" => $form->createView(),
    //         "category" => $category
    //     ]);   
    // }

    // #[Route('/category/{id<\d+>}/delete}', name: 'admin_category_delete', methods: ['POST'])]
    // public function delete(Category $category, Request $request): Response
    // {
    //     if ($this->isCsrfTokenValid('delete_category_'.$category->getId(), $request->request->get('_csrf_token')))
    //     {
    //         $this->addFlash('success', "La catégorie {$category->getName()} a été supprimée");
            
    //         $this->em->remove($category);
    //         $this->em->flush();
    //     }

    //     return $this->redirectToRoute("admin_category_index");
    // }

}

<?php

namespace App\Controller\Admin\Court;


use App\Entity\Court;
use DateTimeImmutable;
use App\Form\AdminCourtFormType;
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


    #[Route('/court/create', name: 'admin_court_create', methods: ['GET'])]
    public function create(): Response
    {
        $court = new Court();

        $court  ->setCourtNumber(\count($this->courtRepository->findAll()) + 1)
                ->setAvailable(false)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());

        $this->em->persist($court);

        $this->em->flush();
        
        $this->addFlash('success', "Une nouvelle piste a été ajoutée avec succès.");

        return $this->redirectToRoute('admin_court_index');
    }

    #[Route('/court/{id<\d+>}/available}', name: 'admin_court_available', methods: ['GET', 'POST'])]
    public function available(Court $court): Response
    {
        $court  ->setAvailable(!$court->isAvailable())
                ->setUpdatedAt(new DateTimeImmutable());

        $this->em->persist($court);
        $this->em->flush();

        $this->addFlash('success', "La disponibilité de la piste {$court->getCourtNumber()} a été modifée");
        return $this->redirectToRoute('admin_court_index');
    }

    #[Route('/court/{id<\d+>}/edit', name: 'admin_court_edit', methods: ['GET', 'POST'])]
    public function edit(Court $court, Request $request): Response
    {    
        $form = $this->createForm(AdminCourtFormType::class, $court);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $court->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($court);
            $this->em->flush();

            $this->addFlash("success", "Le numéro du court a été modifié");

            return $this->redirectToRoute("admin_court_index");
        }

        return $this->render("pages/admin/court/edit.html.twig", [
            'court' => $court,
            'form' => $form->createView()
        ]);
    }

    #[Route('/court/{id<\d+>}/delete}', name: 'admin_court_delete', methods: ['POST'])]
    public function delete(Court $court, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_court_'.$court->getId(), $request->request->get('_csrf_token')))
        {
            $this->addFlash('success', "La piste {$court->getCourtNumber()} a été supprimée");
            
            $this->em->remove($court);
            $this->em->flush();
        }

        return $this->redirectToRoute("admin_court_index");
    }

}

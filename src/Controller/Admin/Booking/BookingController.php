<?php

namespace App\Controller\Admin\Booking;

use DateTimeImmutable;
use App\Entity\Booking;
use App\Form\AdminBookingFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class BookingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookingRepository $bookingRepository
    )
    {
    }

    #[Route('/booking/list', name: 'admin_booking_index')]
    public function index(): Response
    {
        $bookings = $this->bookingRepository->findAll();

        return $this->render('pages/admin/booking/index.html.twig', [
            'bookings' => $bookings
        ]);
    }

    #[Route('/booking/{id<\d+>}/edit', name: 'admin_booking_edit', methods: ['GET', 'POST'])]
    public function edit(Booking $booking, Request $request): Response
    {    
        $form = $this->createForm(AdminBookingFormType::class, $booking);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $booking->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($booking);
            $this->em->flush();

            $this->addFlash("success", "La réservation a été modifiée");

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render("pages/admin/booking/edit.html.twig", [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    #[Route('/booking/{id<\d+>}/delete}', name: 'admin_booking_delete', methods: ['POST'])]
    public function delete(Booking $booking, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_booking_'.$booking->getId(), $request->request->get('_csrf_token')))
        {
            $this->addFlash('success', "La réservation a été supprimée");
            
            $this->em->remove($booking);
            $this->em->flush();
        }

        return $this->redirectToRoute("admin_booking_index");
    }
}

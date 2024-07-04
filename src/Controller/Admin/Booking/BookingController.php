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
        $futureBookings = $this->bookingRepository->findFutureBookings();
        $pastBookings = $this->bookingRepository->findPastBookings();


        return $this->render('pages/admin/booking/index.html.twig', [
            'futureBookings' => $futureBookings,
            'pastBookings' => $pastBookings
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

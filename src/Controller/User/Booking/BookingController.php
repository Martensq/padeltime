<?php

namespace App\Controller\User\Booking;

use App\Repository\SettingRepository;
use App\Service\DateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    private $dateService;

    public function __construct(DateService $dateService)
    {
        $this->dateService = $dateService;
    }
    
    #[Route('/reservation', name: 'user_booking_index')]
    public function index(SettingRepository $settingRepository): Response
    {
        $nextFourteenDays = $this->dateService->getNextFourteenDays();

        return $this->render('pages/user/booking/index.html.twig', [
            'days' => $nextFourteenDays,
            'setting' => $settingRepository->find(4)
        ]);
    }
}

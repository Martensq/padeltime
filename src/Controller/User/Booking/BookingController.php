<?php

namespace App\Controller\User\Booking;


use DateTime;
use App\Service\SlotService;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class BookingController extends AbstractController
{
    private $slotService;

    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function getNextFourteenDays(): array
    {
        $days = [];
        $currentDate  = new DateTime();

        for ($i = 0; $i < 14; $i++) {
            $days[] = (clone $currentDate)->modify('+' . $i . ' day');
        }

        return $days;
    }
    
    #[Route('/reservation', name: 'user_booking_index')]
    public function index(SettingRepository $settingRepository): Response
    {
        $dates = $this->getNextFourteenDays();

        // Pour chaque date, appelez le service de booking, récupérer les slot de la date et les render

        return $this->render('pages/user/booking/index.html.twig', [
            'days' => $dates,
            'setting' => $settingRepository->find(4)
        ]);

        
    }
}

<?php

namespace App\Service;

use DateTime;
use App\Repository\CourtRepository;
use App\Repository\BookingRepository;

class SlotService
{
    private CourtRepository $courtRepository;
    private BookingRepository $bookingRepository;
    
    public function __construct(CourtRepository $courtRepository,BookingRepository $bookingRepository)
    {
        $this->courtRepository = $courtRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function generateSlotsForDay(DateTime $date): array
    {
        $slots = [];
        $startHour = 10;
        $endHour = 22;
        $step = 1;
        $maxDuration = 2;
        $durationStep = 1;
        $courts = $this->courtRepository->findBy(['available' => true]);

        // On récupère tous les booking de la date entre 00h et 23h59

        $startDay = new \DateTime($date->format("Y-m-d")." 00:00:00");
        $endDay   = new \DateTime($date->format("Y-m-d")." 23:59:59");

        $bookings = $this->bookingRepository->findBetween($startDay, $endDay);

       // dd($bookings);


        for ($hour = $startHour; $hour <= $endHour - 1; $hour+=$step) {
            $availableCourts = count($courts);
            $availableDurations = [];
            $startTime = (clone $date)->setTime($hour, 0);

            for ($duration = 1; $duration <= $maxDuration; $duration += $durationStep) { 
                $endTime = (clone $date)->setTime($hour+$duration, 0);

                // Si bookings contient un booking dont la startDate ou la endDate se trouve between le startTime et le endTime du créneau, alors on ne l'ajoute pas à availableDurations
                if ($bookings == $startTime AND $bookings != $endTime)
                {
                    $availableDurations[] = $duration;
                }
            }

            $slots[] = [
                    'startTime' => clone $startTime,
                    'durations' => $availableDurations,
                    'availableCourts' => $availableCourts,
                ];
        }

        return $slots;
    }
}
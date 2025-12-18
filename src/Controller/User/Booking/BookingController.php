<?php

namespace App\Controller\User\Booking;

use DateTimeImmutable;
use App\Entity\Booking;
use App\Repository\CourtRepository;
use App\Repository\BookingRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class BookingController extends AbstractController
{
    private CourtRepository $courtRepository;
    private SettingRepository $settingRepository;
    private BookingRepository $bookingRepository;
    private EntityManagerInterface $em;


    public function __construct(
        CourtRepository $courtRepository,
        SettingRepository $settingRepository,
        BookingRepository $bookingRepository,
        EntityManagerInterface $em
    )
    {
        $this->courtRepository = $courtRepository;
        $this->settingRepository = $settingRepository;
        $this->bookingRepository = $bookingRepository;
        $this->em = $em;
    }

    private function getNextFifteenDays(): array
    {
        $days = [];
        $currentDate  = new DateTimeImmutable();

        for ($i = 0; $i < 15; $i++) {
            $days[] = (clone $currentDate)->modify('+' . $i . ' day');
        }

        return $days;
    }

    private function dateExistsInArray(DateTimeImmutable $dateTime, array $nextFifteenDays): bool
    {
        $formattedDate = $dateTime->format('Y-m-d');
        foreach ($nextFifteenDays as $date) {
            if ($date->format('Y-m-d') === $formattedDate) {
                return true;
            }
        }
        return false;
    }

    private function getUnavailableCourts(DateTimeImmutable $date, array $courts): array
    {
        $unavailableCourts = [];
    
        foreach ($courts as $court) {
            $unavailableFrom = $court->getUnavailableFrom();
            $unavailableTo = $court->getUnavailableTo();
    
            if ($unavailableFrom && $unavailableTo) {
                if (($unavailableFrom <= $date && $unavailableTo >= $date)) {
                    $unavailableCourts[] = $court;
                }
            }
        }
    
        return $unavailableCourts;
    }

    private function getCurrentHour(DateTimeImmutable $date): ?int
    {
        $today = new DateTimeImmutable();
        return ($date->format('Y-m-d') === $today->format('Y-m-d')) ? $today->format('H') : null;
    }

    private function getTwoHourBookingsForPreviousHour(array $previousHourBookings): int
    {
        $twoHoursBookingsPreviousHour = 0;

        foreach ($previousHourBookings as $previousHourBooking) {
            $bookingDuration = $previousHourBooking->getDuration();
            if ($bookingDuration == 2) {
                $twoHoursBookingsPreviousHour++;
            }
        }

        return $twoHoursBookingsPreviousHour;
    }

    private function getTwoHourBookingsForCurrentHour(array $currentHourBookings): int
    {
        $twoHoursBookingsCurrentHour = 0;

        foreach ($currentHourBookings as $currentHourBooking) {
            $bookingDuration = $currentHourBooking->getDuration();
            if ($bookingDuration == 2) {
                $twoHoursBookingsCurrentHour++;
            }
        }

        return $twoHoursBookingsCurrentHour;
    }

    private function generateSlotsForDay(DateTimeImmutable $date): array
    {
        $slots = [];
        $isWeekend = $date->format('D') == 'Sat' || $date->format('D') == 'Sun' ? true : false;
        $startHour = 10;
        $endHour = $isWeekend ? 23 : 21;
        $slotHourStep = 1;
        $peakHoursPrice = $this->settingRepository->findOneBy([])->getPeakHoursPrice();
        $offPeakHoursPrice = $this->settingRepository->findOneBy([])->getOffPeakHoursPrice();

        // Récupérer tous les terrains
        $courts = $this->courtRepository->findAll();

        // Récupérer le nombre de terrains en période d'indisponibilité
        $unavailableCourts = count($this->getUnavailableCourts($date, $courts));

        // Obtenir toutes les réservations en cours pour la date
        $bookingsForDay = $this->bookingRepository->findBookingsByDate($date);

        // Initialiser le tableau des créneaux de réservation
        $bookingSlot = [];

        // Regrouper les réservations par heure de début
        foreach ($bookingsForDay as $booking) {
            $hour = $booking->getStartDate()->format('H');
            $bookingSlot[$hour] = $bookingSlot[$hour] ?? [];
            $bookingSlot[$hour][] = $booking;
        }
        

        // Boucler à travers chaque heure dans la plage horaire
        for ($hour = $startHour; $hour < $endHour; $hour+=$slotHourStep) {
            // Ignorer les heures déjà passées si la date est aujourd'hui
            if ($this->getCurrentHour($date) !== null && $hour <= $this->getCurrentHour($date)) {
                continue;
            }

            // Récupérer les réservations de l'heure précédente, ou un tableau vide si aucune réservation n'existe
            $previousHourBookings = $bookingSlot[$hour - 1] ?? [];
            $twoHoursBookingsPreviousHour = $this->getTwoHourBookingsForPreviousHour($previousHourBookings);

            // Récupérer les réservations de l'heure actuelle, ou un tableau vide si aucune réservation n'existe
            $currentHourBookings = $bookingSlot[$hour] ?? [];

            // Calculer les terrains disponibles en tenant compte des réservations des heures courantes et précédentes et des terrains indisponibles
            $availableCourts = count($courts) - $unavailableCourts - count($currentHourBookings) - $twoHoursBookingsPreviousHour;

            // S'il y a des terrains disponibles, générer des créneaux pour cette heure
            if ($availableCourts > 0) {
                $maxDuration = $hour == $endHour-1 ? 1 : 2; // Limiter la durée à 1 heure à 20h ou 22h
                $durationStep = 1;
                $availableDurations = [];
                $pricePerHour = $isWeekend || $hour > 17 ? $peakHoursPrice : $offPeakHoursPrice;
                $price = [];
                $startTime = (clone $date)->setTime($hour, 0);

                // Déterminer les durées disponibles pour les réservations
                for ($duration = 1; $duration <= $maxDuration; $duration += $durationStep) {
                    if ($duration == $maxDuration) {
                        // Récupère les réservations de 2h uniquement 
                        $twoHoursBookingsCurrentHour = $this->getTwoHourBookingsForCurrentHour($currentHourBookings);
                        
                        if (count($bookingSlot[$hour + 1] ?? []) < count($courts) - $unavailableCourts - $twoHoursBookingsCurrentHour) {
                            $availableDurations[] = $duration;
                            $price[] = $pricePerHour * $duration;
                        }
                    } else {
                        $availableDurations[] = $duration;
                        $price[] = $pricePerHour * $duration;
                    }

                }
                // Ajouter le créneau au tableau
                $slots[$hour] = [
                    'startTime' => $startTime,
                    'durations' => $availableDurations,
                    'price' => $price,
                    'availableCourts' => $availableCourts,
                ];
            }
        }
        return $slots;
    }

    #[Route('/reservation/{annee}/{mois}/{jour}', name: 'user_booking_index')]
    public function index($annee, $mois, $jour): Response
    {
        // Récupérer la date à partir des paramètres de l'URL
        $tz = new \DateTimeZone('Europe/Paris');
        $dateRecup = \DateTimeImmutable::createFromFormat('Y-m-d H:i', "$annee-$mois-$jour 00:00", $tz);

        if ($dateRecup === false) {
            throw $this->createNotFoundException('Date invalide');
        }

        // Récupère les quinze prochains jours à partir de la date actuelle.
        $dates = $this->getNextFifteenDays();

        // Si la date n'est pas dans les quinze prochains jours, rediriger vers la date d'aujourd'hui
        if (!$this->dateExistsInArray($dateRecup, $dates)) {
            return $this->redirectToRoute('user_booking_index', [
                'annee' => date('Y'),
                'mois' => date('m'),
                'jour' => date('d')
            ]);
        }

        // Générer les créneaux pour le jour spécifié
        $slotsForDay = $this->generateSlotsForDay($dateRecup);        

        return $this->render('pages/user/booking/index.html.twig', [
            'days' => $dates,
            'selectedDay' => $dateRecup,
            'slotsForDay' => $slotsForDay,
            'setting' => $this->settingRepository->findOneBy([])
        ]);
    }


    #[Route('/reservation/confirmation/{annee}/{mois}/{jour}/{heure}/{duree}', name: 'user_booking_confirm')]
    public function newBookingConfirm($annee, $mois, $jour, $heure, $duree): Response
    {
        $tz = new \DateTimeZone('Europe/Paris');

        $date = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            sprintf('%04d-%02d-%02d %02d:00', $annee, $mois, $jour, $heure),
            $tz
        );

        if ($date === false) {
            return $this->redirectToRoute('user_booking_index', [
                'annee' => date('Y'),
                'mois' => date('m'),
                'jour' => date('d'),
            ]);
        }

        $court = $this->courtRepository->findAvailableCourtForHour($date, $heure, $duree);
        $slotsForDay = $this->generateSlotsForDay($date);

        if (!isset($slotsForDay[$heure]) || !in_array($duree, $slotsForDay[$heure]['durations'])) {
            return $this->redirectToRoute('user_booking_index', [
                'annee' => date('Y'),
                'mois' => date('m'),
                'jour' => date('d'),
            ]);
        }

        $selectedSlot = $slotsForDay[$heure];
        $price = $selectedSlot['price'][array_search($duree, $selectedSlot['durations'])];

        return $this->render("pages/user/booking/confirm.html.twig", [
            'date' => $date,
            'heure' => (int) $heure,     // ✅ on passe l'heure explicitement
            'duree' => (int) $duree,
            'price' => $price,
            'court' => $court,
            'setting' => $this->settingRepository->findOneBy([])
        ]);
    }
    
    #[Route('/reservation/nouvelle/{annee}/{mois}/{jour}/{heure}/{duree}/{price}', name: 'user_booking_create')]
    public function create($annee, $mois, $jour, $heure, $duree, $price): Response
    {
        $tz = new \DateTimeZone('Europe/Paris');
        $now = new \DateTimeImmutable('now', $tz);
        // Créer un objet DateTimeImmutable à partir des paramètres de date et heure
        $date = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            sprintf('%04d-%02d-%02d %02d:00', $annee, $mois, $jour, $heure),
            $tz
        );

        if ($date === false) {
            // fallback propre si parsing impossible
            return $this->redirectToRoute('user_booking_index', [
                'annee' => date('Y'),
                'mois' => date('m'),
                'jour' => date('d'),
            ]);
        }

        $slotsForDay = $this->generateSlotsForDay($date);

        // Vérifier si le créneau demandé existe dans les créneaux générés
        if (!isset($slotsForDay[$heure]) || !in_array($duree, $slotsForDay[$heure]['durations'])) {
            return $this->redirectToRoute('user_booking_index', [
                'annee' => date('Y'),
                'mois' => date('m'),
                'jour' => date('d'),
            ]);
        }

        // Récupérer le court disponible pour l'heure et la durée sélectionnée
        $availableCourt = $this->courtRepository->findAvailableCourtForHour($date, $heure, $duree);

        // Créer une nouvelle réservation avec le court disponible trouvé
        $booking = new Booking();
        $booking->setStartDate($date)
                ->setDuration($duree)
                ->setPrice($price)
                ->setCourt($availableCourt)
                ->setUser($this->getUser())
                ->setCreatedAt($now)
                ->setUpdatedAt($now);

        $this->em->persist($booking);
        $this->em->flush();

        $this->addFlash("success", "La réservation a été confirmée sur la piste " . $availableCourt->getCourtNumber());

        return $this->redirectToRoute('user_booking_index', [
            'annee' => $annee,
            'mois' => $mois,
            'jour' => $jour
        ]);
    }
}

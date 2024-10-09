<?php

namespace App\Repository;

use App\Entity\Court;
use DateTimeImmutable;
use App\Repository\BookingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Court>
 */
class CourtRepository extends ServiceEntityRepository
{
    private BookingRepository $bookingRepository;

    public function __construct(ManagerRegistry $registry, BookingRepository $bookingRepository)
    {
        parent::__construct($registry, Court::class);
        $this->bookingRepository = $bookingRepository;
    }

    public function findAvailableCourtForHour(DateTimeImmutable $date, int $hour, int $duration = 1): ?Court
    {
        // Récupérer tous les courts
        $courts = $this->findAll();

        // Récupérer les réservations de l'heure actuelle
        $bookingsForDay = $this->bookingRepository->findBookingsByDate($date);
        
        // Initialiser un tableau des terrains indisponibles pour cette heure
        $unavailableCourts = [];

        // Parcourir chaque réservation pour vérifier la disponibilité des courts
        foreach ($bookingsForDay as $booking) {
            $bookingHour = $booking->getStartDate()->format('H');
            $bookingDuration = $booking->getDuration();

            // S'il y a une réservation de 2h à l'heure précédente, le court concerné par la réservation est indisponible pour l'heure actuelle
            if (($bookingHour == $hour - 1 && $bookingDuration == 2) || $bookingHour == $hour) {
                $unavailableCourts[] = $booking->getCourt();
            }
            // Si on séléctionne une durée de 2h, il faut également vérifier les réservations de l'heure suivante
            if ($duration == 2 && $bookingHour == $hour + 1) {
                $unavailableCourts[] = $booking->getCourt();
            }
        }

        // Vérifier les indisponibilités des courts pour la date donnée
        foreach ($courts as $court) {
            if ($court->getUnavailableFrom() && $court->getUnavailableTo()) {
                if (($court->getUnavailableFrom() <= $date) && ($court->getUnavailableTo() >= $date)) {
                    $unavailableCourts[] = $court;
                }
            }
        }

        // Parcourir tous les courts et retourner le premier qui n'est pas dans la liste des indisponibles
        foreach ($courts as $court) {
            if (!in_array($court, $unavailableCourts, true)) {
                return $court;
            }
        }
        // Si aucun court disponible n'est trouvé, retourner null
        return null;
    }
}

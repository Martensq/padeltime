<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    //    /**
    //     * @return Booking[] Returns an array of Booking objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Booking
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    
    
    public function findBookingsByDate($date = null, $current = true, $user = null): array
    {
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('b');
    
        if ($date) {
            $qb->where('b.startDate LIKE :date')
               ->setParameter('date', $date->format('Y-m-d') . '%');
        }
    
        if ($user) {
            $qb->andWhere('b.user = :user')
               ->setParameter('user', $user);
        }
    
        // Filtrer les réservations futures ou passées
        if ($current) {
            $qb->andWhere('b.startDate > :now')
               ->setParameter('now', $now);
        } else {
            $qb->andWhere('b.startDate < :now')
               ->setParameter('now', $now);
        }
    
        return $qb
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

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
    

    public function findByDate($date)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.startDate LIKE :date')
            ->setParameter('date', $date->format('Y-m-d') . '%')
            ->getQuery()
            ->getResult();
    }

    public function findFutureBookingsByUser($user): array
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->andWhere('b.startDate > :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFutureBookings(): array
    {
        $now = new \DateTime();
    
        return $this->createQueryBuilder('b')
            ->where('b.startDate > :now')
            ->setParameter('now', $now)
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPastBookings(): array
    {
        $now = new \DateTime();
    
        return $this->createQueryBuilder('b')
            ->where('b.startDate < :now')
            ->setParameter('now', $now)
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

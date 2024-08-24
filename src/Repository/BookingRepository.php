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

public function findFutureBookingsByUser($user)
{
    return $this->createQueryBuilder('b')
        ->where('b.user = :user')
        ->andWhere('b.date_booking > :now')
        ->setParameter('user', $user)
        ->setParameter('now', new \DateTime())
        ->orderBy('b.date_booking', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findPastBookingsByUser($user)
{
    return $this->createQueryBuilder('b')
        ->where('b.user = :user')
        ->andWhere('b.date_booking <= :now')
        ->setParameter('user', $user)
        ->setParameter('now', new \DateTime())
        ->orderBy('b.date_booking', 'DESC')
        ->getQuery()
        ->getResult();
}

}

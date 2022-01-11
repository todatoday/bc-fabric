<?php

namespace App\Repository;

use App\Entity\Bijoux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bijoux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bijoux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bijoux[]    findAll()
 * @method Bijoux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BijouxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bijoux::class);
    }



    public function findBestBijouxs($limit)
    {
        return $this->createQueryBuilder('b')
            ->select('b as bijoux, AVG(cb.rating) as avgRatings')
            ->join('b.commentBs', 'cb')
            ->groupBy('b')
            ->orderBy('avgRatings', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Bijoux[] Returns an array of Bijoux objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bijoux
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

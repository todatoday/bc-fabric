<?php

namespace App\Repository;

use App\Entity\Couture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Couture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Couture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Couture[]    findAll()
 * @method Couture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoutureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Couture::class);
    }


    public function findBestCoutures($limit)
    {
        return $this->createQueryBuilder('c')
            ->select('c as couture, AVG(cc.rating) as avgRatings')
            ->join('c.commentCs', 'cc')
            ->groupBy('c')
            ->orderBy('avgRatings', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Couture[] Returns an array of Couture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Couture
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

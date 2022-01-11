<?php

namespace App\Repository;

use App\Entity\CommentC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentC|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentC|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentC[]    findAll()
 * @method CommentC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentC::class);
    }

    // /**
    //  * @return CommentC[] Returns an array of CommentC objects
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
    public function findOneBySomeField($value): ?CommentC
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

<?php

namespace App\Repository;

use App\Entity\CommentB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentB|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentB|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentB[]    findAll()
 * @method CommentB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentBRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentB::class);
    }

    // /**
    //  * @return CommentB[] Returns an array of CommentB objects
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
    public function findOneBySomeField($value): ?CommentB
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

<?php

namespace App\Repository;

use App\Entity\ImageBijoux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageBijoux|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageBijoux|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageBijoux[]    findAll()
 * @method ImageBijoux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageBijouxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageBijoux::class);
    }

    // /**
    //  * @return ImageBijoux[] Returns an array of ImageBijoux objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageBijoux
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

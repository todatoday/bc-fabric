<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private $manager;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function getStats()
    {
        $users      = $this->getUsersCount();
        $bijouxs    = $this->getBijouxsCount();
        $coutures   = $this->getCouturesCount();
        $commentBs  = $this->getCommentBsCount();
        $commentCs  = $this->getCommentCsCount();

        return compact('users', 'bijouxs', 'coutures', 'commentBs', 'commentCs');
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')
            ->getSingleScalarResult();
    }


    public function getBijouxsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Bijoux b')
            ->getSingleScalarResult();
    }


    public function getCouturesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Couture c')
            ->getSingleScalarResult();
    }


    public function getCommentBsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(cb) FROM App\Entity\CommentB cb')
            ->getSingleScalarResult();
    }


    public function getCommentCsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(cc) FROM App\Entity\CommentC cc')
            ->getSingleScalarResult();
    }



    public function getBijouxsStats($direction)
    {
        // BIJOUX
        return $this->manager->createQuery(
            'SELECT AVG(cb.rating) as note, b.title, b.id, u.firstName, u.lastName, u.picture
                FROM App\Entity\CommentB cb
                JOIN cb.bijoux b
                JOIN b.author u
                GROUP BY b
                ORDER BY note ' . $direction
        )
            ->setMaxResults(5)
            ->getResult();
    }


    public function getCouturesStats($direction)
    {
        // COUTURE 
        return $this->manager->createQuery(
            'SELECT AVG(cc.rating) as note, c.title, c.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\CommentC cc
            JOIN cc.couture c
            JOIN c.author u
            GROUP BY c
            ORDER BY note ' . $direction
        )
            ->setMaxResults(5)
            ->getResult();
    }


    // public function getBestBijouxs()
    // {
    //     // BIJOUX
    //     return $this->manager->createQuery(
    //         'SELECT AVG(cb.rating) as note, b.title, b.id, u.firstName, u.lastName, u.picture
    //         FROM App\Entity\CommentB cb
    //         JOIN cb.bijoux b
    //         JOIN b.author u
    //         GROUP BY b
    //         ORDER BY note DESC'
    //     )
    //         ->setMaxResults(5)
    //         ->getResult();
    // }


    // public function getWorstBijouxs()
    // {
    //     return $this->manager->createQuery(
    //         'SELECT AVG(cb.rating) as note, b.title, b.id, u.firstName, u.lastName, u.picture
    //         FROM App\Entity\CommentB cb
    //         JOIN cb.bijoux b
    //         JOIN b.author u
    //         GROUP BY b
    //         ORDER BY note ASC'
    //     )
    //         ->setMaxResults(5)
    //         ->getResult();
    // }



    // public function getBestCoutures()
    // {
    //     // COUTURE 
    //     return $this->manager->createQuery(
    //         'SELECT AVG(cc.rating) as note, c.title, c.id, u.firstName, u.lastName, u.picture
    //         FROM App\Entity\CommentC cc
    //         JOIN cc.couture c
    //         JOIN c.author u
    //         GROUP BY c
    //         ORDER BY note DESC'
    //     )
    //         ->setMaxResults(5)
    //         ->getResult();
    // }


    // public function getWorstCoutures()
    // {
    //     return $this->manager->createQuery(
    //         'SELECT AVG(cc.rating) as note, c.title, c.id, u.firstName, u.lastName, u.picture
    //         FROM App\Entity\CommentC cc
    //         JOIN cc.couture c
    //         JOIN c.author u
    //         GROUP BY c
    //         ORDER BY note ASC'
    //     )
    //         ->setMaxResults(5)
    //         ->getResult();
    // }
}

<?php

namespace App\Controller;

use App\Repository\BijouxRepository;
use App\Repository\CoutureRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */

    public function home(BijouxRepository $bijouxRepo, CoutureRepository $coutureRepo, UserRepository $userRepo)
    {
        return $this->render(
            'home.html.twig',
            [
                'bijouxs'  => $bijouxRepo->findBestBijouxs(3),
                'coutures' => $coutureRepo->findBestCoutures(5),
                'users'    => $userRepo->findBestUsers(2)
            ]
        );
    }
}

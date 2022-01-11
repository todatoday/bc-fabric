<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService): Response
    {
        // Variable de appel de StatsService
        $stats = $statsService->getStats();

        // BIJOUX
        $bestBijouxs  = $statsService->getBijouxsStats('DESC');
        $worstBijouxs = $statsService->getBijouxsStats('ASC');

        // COUTURE 
        $bestCoutures  = $statsService->getCouturesStats('DESC');
        $worstCoutures = $statsService->getCouturesStats('ASC');


        // Ont returne et ont passe les variable a Twig
        return $this->render('admin/dashboard/index.html.twig', [
            'stats'         => $stats,
            'bestBijouxs'   => $bestBijouxs,
            'bestCoutures'  => $bestCoutures,
            'worstBijouxs'  => $worstBijouxs,
            'worstCoutures' => $worstCoutures
        ]);
    }
}

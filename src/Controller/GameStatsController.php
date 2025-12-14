<?php

namespace App\Controller;

use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameStatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(StatsService $statsService): Response
    {
        // Récupérer les vraies stats en temps réel
        $globalStats = $statsService->calculateGlobalStats();
        $todayStats = $statsService->getTodayStats();
        $topPlayers = $statsService->getTopPlayers(10);

        return $this->render('game_stats/index.html.twig', [
            'globalStats' => $globalStats,
            'todayStats' => $todayStats,
            'topPlayers' => $topPlayers,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\GameSettingsRepository;
use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        StatsService           $statsService,
        GameSettingsRepository $settingsRepository
    ): Response
    {
        // Récupérer les vraies stats en temps réel
        $globalStats = $statsService->calculateGlobalStats();
        $topPlayers = $statsService->getTopPlayers(3);
        $recentScores = $statsService->getRecentScores(5);
        $todayStats = $statsService->getTodayStats();

        // Récupérer les settings
        $settings = $settingsRepository->findOneBy(['id' => 1]);

        if (!$settings) {
            $settings = (object)[
                'clawSpeed' => 5.0,
                'timeLimit' => 60,
                'difficulty' => 'Medium',
                'itemSpawnRate' => 2.5,
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'globalStats' => $globalStats,
            'topPlayers' => $topPlayers,
            'recentScores' => $recentScores,
            'todayStats' => $todayStats,
            'settings' => $settings,
        ]);
    }
}


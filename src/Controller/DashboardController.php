<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use App\Repository\GameStatsRepository;
use App\Repository\GameSettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        ScoreRepository        $scoreRepository,
        GameStatsRepository    $statsRepository,
        GameSettingsRepository $settingsRepository
    ): Response
    {
        $scores = $scoreRepository->findBy([], ['score' => 'DESC'], 10);
        $stats = $statsRepository->findOneBy(['id' => 1]);
        $settings = $settingsRepository->findOneBy(['id' => 1]);

        if (!$settings) {
            $settings = (object)[
                'clawSpeed' => 'non défini',
                'timeLimit' => 'non défini',
                'difficulty' => 'non défini',
                'itemSpawnRate' => 'non défini',
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'scores' => $scores,
            'stats' => $stats,
            'settings' => $settings,
        ]);
    }
}


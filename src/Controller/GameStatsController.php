<?php

namespace App\Controller;

use App\Repository\GameStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameStatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(GameStatsRepository $statsRepository): Response
    {
        $stats = $statsRepository->findOneBy([], ['id' => 'DESC']);

        return $this->render('game_stats/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}

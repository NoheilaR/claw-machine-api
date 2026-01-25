<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/classement', name: 'leaderboard')]
    public function index(Request $request, ScoreRepository $scoreRepository): Response
    {
        $period = $request->query->get('period', 'all');

        // Calculer la date de début selon la période
        $startDate = match($period) {
            'today' => new \DateTime('today'),
            'week' => new \DateTime('monday this week'),
            'month' => new \DateTime('first day of this month'),
            default => null,
        };

        // Récupérer les scores
        $qb = $scoreRepository->createQueryBuilder('s')
            ->orderBy('s.score', 'DESC')
            ->setMaxResults(50);

        if ($startDate) {
            $qb->where('s.playedAt >= :startDate')
               ->setParameter('startDate', $startDate);
        }

        $scores = $qb->getQuery()->getResult();

        // Calculer des stats pour la période
        $statsQb = $scoreRepository->createQueryBuilder('s')
            ->select(
                'COUNT(s.id) as totalGames',
                'MAX(s.score) as highestScore',
                'AVG(s.score) as averageScore'
            );

        if ($startDate) {
            $statsQb->where('s.playedAt >= :startDate')
                    ->setParameter('startDate', $startDate);
        }

        $stats = $statsQb->getQuery()->getSingleResult();

        return $this->render('leaderboard/index.html.twig', [
            'scores' => $scores,
            'period' => $period,
            'stats' => [
                'totalGames' => (int) $stats['totalGames'],
                'highestScore' => (int) ($stats['highestScore'] ?? 0),
                'averageScore' => round($stats['averageScore'] ?? 0),
            ],
        ]);
    }
}

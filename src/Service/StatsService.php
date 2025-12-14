<?php

namespace App\Service;

use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    public function __construct(
        private ScoreRepository $scoreRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Calculer les statistiques globales en temps réel
     */
    public function calculateGlobalStats(): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Récupérer les stats globales
        $result = $qb->select(
            'COUNT(s.id) as totalGames',
            'AVG(s.duration) as averageDuration',
            'MAX(s.score) as highestScore',
            'SUM(s.score) as totalPoints',
            'COUNT(DISTINCT s.playerName) as uniquePlayers'
        )
        ->from('App\Entity\Score', 's')
        ->getQuery()
        ->getSingleResult();

        // Calculer le score moyen
        $averageScore = $result['totalGames'] > 0
            ? round($result['totalPoints'] / $result['totalGames'], 2)
            : 0;

        return [
            'totalGames' => (int) $result['totalGames'],
            'averageDuration' => $result['averageDuration'] ? round($result['averageDuration'], 1) : 0,
            'highestScore' => (int) $result['highestScore'] ?: 0,
            'averageScore' => $averageScore,
            'uniquePlayers' => (int) $result['uniquePlayers'],
            'totalPoints' => (int) $result['totalPoints'],
        ];
    }

    /**
     * Récupérer le top N des joueurs
     */
    public function getTopPlayers(int $limit = 3): array
    {
        return $this->scoreRepository->createQueryBuilder('s')
            ->orderBy('s.score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupérer les scores récents
     */
    public function getRecentScores(int $limit = 5): array
    {
        return $this->scoreRepository->createQueryBuilder('s')
            ->orderBy('s.playedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtenir les statistiques du jour
     */
    public function getTodayStats(): array
    {
        $today = new \DateTime('today');

        $qb = $this->entityManager->createQueryBuilder();

        $result = $qb->select(
            'COUNT(s.id) as gamesToday',
            'MAX(s.score) as bestScoreToday'
        )
        ->from('App\Entity\Score', 's')
        ->where('s.playedAt >= :today')
        ->setParameter('today', $today)
        ->getQuery()
        ->getSingleResult();

        return [
            'gamesToday' => (int) $result['gamesToday'],
            'bestScoreToday' => (int) $result['bestScoreToday'] ?: 0,
        ];
    }
}

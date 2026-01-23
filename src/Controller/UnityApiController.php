<?php

namespace App\Controller;

use App\Dto\CreateScoreDto;
use App\Entity\GameSettings;
use App\Entity\Score;
use App\Service\ScoreValidator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/unity', name: 'api_unity_')]
class UnityApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private ScoreValidator $scoreValidator,
        private LoggerInterface $logger
    ) {}

    /**
     * Récupérer les paramètres du jeu
     * GET /api/unity/settings/{id}
     */
    #[Route('/settings/{id}', name: 'get_settings', methods: ['GET'])]
    public function getSettings(int $id): JsonResponse
    {
        $settings = $this->entityManager->getRepository(GameSettings::class)->find($id);

        if (!$settings) {
            return $this->json([
                'error' => 'Settings not found',
                'message' => "Les paramètres avec l'ID {$id} n'existent pas"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $settings->getId(),
            'clawSpeed' => $settings->getClawSpeed(),
            'timeLimit' => $settings->getTimeLimit(),
            'difficulty' => $settings->getDifficulty(),
            'itemSpawnRate' => $settings->getItemSpawnRate()
        ]);
    }

    /**
     * Récupérer les paramètres par défaut (ID 1)
     * GET /api/unity/settings/default
     */
    #[Route('/settings/default', name: 'get_default_settings', methods: ['GET'])]
    public function getDefaultSettings(): JsonResponse
    {
        return $this->getSettings(1);
    }

    /**
     * Enregistrer un score
     * POST /api/unity/score (recommandé)
     * GET /api/unity/score?playerName=...&score=...&duration=...&hash=... (pour proxy Anatidae)
     *
     * Body POST (JSON):
     * {
     *   "playerName": "Player1",
     *   "score": 1500,
     *   "duration": 60.5,
     *   "hash": "abc123..." (optionnel en dev)
     * }
     *
     * Query GET:
     * ?playerName=Player1&score=1500&duration=60.5&hash=abc123...
     */
    #[Route('/score', name: 'post_score', methods: ['POST', 'GET'])]
    public function postScore(Request $request): JsonResponse
    {
        try {
            // ========================================
            // SUPPORTER GET ET POST (workaround proxy Anatidae)
            // ========================================
            if ($request->isMethod('POST')) {
                // Méthode POST : lire le body JSON
                $data = json_decode($request->getContent(), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->logger->error('Invalid JSON in POST', [
                        'content' => $request->getContent(),
                        'error' => json_last_error_msg()
                    ]);

                    return $this->json([
                        'error' => 'Invalid JSON',
                        'message' => 'Le corps de la requête doit être un JSON valide'
                    ], Response::HTTP_BAD_REQUEST);
                }

                $this->logger->info('Score received via POST', ['data' => $data]);
            } else {
                // Méthode GET : lire les query parameters
                $data = [
                    'playerName' => $request->query->get('playerName'),
                    'score' => $request->query->get('score'),
                    'duration' => $request->query->get('duration'),
                    'hash' => $request->query->get('hash'),
                    'playedAt' => $request->query->get('playedAt')
                ];

                $this->logger->info('Score received via GET', ['data' => $data]);
            }

            // Créer le DTO
            $dto = new CreateScoreDto();
            $dto->playerName = $data['playerName'] ?? null;
            $dto->score = isset($data['score']) ? (int)$data['score'] : null;
            $dto->duration = isset($data['duration']) ? (float)$data['duration'] : null;
            $dto->hash = $data['hash'] ?? $request->headers->get('X-Score-Hash');
            $dto->playedAt = $data['playedAt'] ?? null;

            // Valider le DTO
            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                $this->logger->warning('Validation failed', [
                    'errors' => $errorMessages,
                    'data' => $data
                ]);

                return $this->json([
                    'error' => 'Validation failed',
                    'message' => 'Les données envoyées sont invalides',
                    'errors' => $errorMessages
                ], Response::HTTP_BAD_REQUEST);
            }

            // Sanitiser le nom
            $sanitizedName = $this->scoreValidator->sanitizePlayerName($dto->playerName);

            // Vérifier la plausibilité
            if (!$this->scoreValidator->isScorePlausible($dto->score, $dto->duration)) {
                $this->logger->warning('Implausible score rejected', [
                    'playerName' => $sanitizedName,
                    'score' => $dto->score,
                    'duration' => $dto->duration,
                    'ip' => $request->getClientIp()
                ]);

                return $this->json([
                    'error' => 'Implausible score',
                    'message' => 'Le score semble anormal par rapport à la durée de jeu'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Valider le hash (en production uniquement)
            $devMode = $_ENV['APP_ENV'] === 'dev';
            if ($dto->hash && !$devMode) {
                $isValid = $this->scoreValidator->validateHash(
                    $sanitizedName,
                    $dto->score,
                    $dto->duration,
                    $dto->hash
                );

                if (!$isValid) {
                    $this->logger->error('Invalid hash detected', [
                        'playerName' => $sanitizedName,
                        'score' => $dto->score,
                        'ip' => $request->getClientIp()
                    ]);

                    return $this->json([
                        'error' => 'Invalid hash',
                        'message' => 'Le hash de sécurité est invalide'
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }

            // Créer l'entité Score
            $score = new Score();
            $score->setPlayerName($sanitizedName);
            $score->setScore($dto->score);
            $score->setDuration($dto->duration);

            // Définir playedAt avec le fuseau horaire Europe/Paris
            $timezone = new \DateTimeZone('Europe/Paris');
            if ($dto->playedAt) {
                try {
                    $score->setPlayedAt(new \DateTime($dto->playedAt, $timezone));
                } catch (\Exception $e) {
                    $score->setPlayedAt(new \DateTime('now', $timezone));
                }
            } else {
                $score->setPlayedAt(new \DateTime('now', $timezone));
            }

            // Sauvegarder
            $this->entityManager->persist($score);
            $this->entityManager->flush();

            $this->logger->info('Score saved successfully', [
                'id' => $score->getId(),
                'playerName' => $sanitizedName,
                'score' => $dto->score,
                'method' => $request->getMethod()
            ]);

            // Retourner la réponse
            return $this->json([
                'success' => true,
                'message' => 'Score enregistré avec succès',
                'data' => [
                    'id' => $score->getId(),
                    'playerName' => $score->getPlayerName(),
                    'score' => $score->getScore(),
                    'duration' => $score->getDuration(),
                    'playedAt' => $score->getPlayedAt()->format('Y-m-d H:i:s')
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            $this->logger->error('Error saving score', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->json([
                'error' => 'Internal server error',
                'message' => 'Une erreur est survenue lors de l\'enregistrement du score'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Récupérer le top des scores
     * GET /api/unity/leaderboard?limit=10
     */
    #[Route('/leaderboard', name: 'get_leaderboard', methods: ['GET'])]
    public function getLeaderboard(Request $request): JsonResponse
    {
        $limit = $request->query->getInt('limit', 10);
        $limit = min($limit, 100); // Maximum 100 scores

        $scores = $this->entityManager->getRepository(Score::class)
            ->createQueryBuilder('s')
            ->orderBy('s.score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $data = array_map(function(Score $score) {
            return [
                'id' => $score->getId(),
                'playerName' => $score->getPlayerName(),
                'score' => $score->getScore(),
                'duration' => $score->getDuration(),
                'playedAt' => $score->getPlayedAt()->format('Y-m-d H:i:s')
            ];
        }, $scores);

        return $this->json([
            'success' => true,
            'count' => count($data),
            'scores' => $data
        ]);
    }

    /**
     * Endpoint de test pour vérifier que l'API fonctionne
     * GET /api/unity/ping
     */
    #[Route('/ping', name: 'ping', methods: ['GET'])]
    public function ping(): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => 'Claw Machine API is running',
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
            'environment' => $_ENV['APP_ENV'] ?? 'unknown'
        ]);
    }
}

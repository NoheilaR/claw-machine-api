<?php

namespace App\State;

use App\Entity\Score;
use App\Service\ScoreValidator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Psr\Log\LoggerInterface;

class ScoreProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ScoreValidator $validator,
        private RequestStack $requestStack,
        private LoggerInterface $logger
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Score
    {
        if (!$data instanceof Score) {
            throw new \InvalidArgumentException('Expected Score entity');
        }

        // Récupérer la requête HTTP
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            throw new BadRequestHttpException('No request found');
        }

        // 1. Récupérer le hash depuis le header
        $receivedHash = $request->headers->get('X-Score-Hash');

        // Mode développement : permettre les requêtes sans hash (optionnel)
        $devMode = $_ENV['APP_ENV'] === 'dev';

        if (!$receivedHash && !$devMode) {
            $this->logger->warning('Score submission without hash', [
                'playerName' => $data->getPlayerName(),
                'ip' => $request->getClientIp()
            ]);
            throw new UnauthorizedHttpException('', 'Missing X-Score-Hash header');
        }

        // 2. Sanitiser le nom du joueur
        $originalName = $data->getPlayerName();
        $sanitizedName = $this->validator->sanitizePlayerName($originalName);
        $data->setPlayerName($sanitizedName);

        if ($originalName !== $sanitizedName) {
            $this->logger->info('Player name sanitized', [
                'original' => $originalName,
                'sanitized' => $sanitizedName
            ]);
        }

        // 3. Vérifier la plausibilité du score
        if (!$this->validator->isScorePlausible($data->getScore(), $data->getDuration())) {
            $this->logger->warning('Implausible score detected', [
                'playerName' => $sanitizedName,
                'score' => $data->getScore(),
                'duration' => $data->getDuration(),
                'ip' => $request->getClientIp()
            ]);
            throw new BadRequestHttpException('Implausible score detected');
        }

        // 4. Valider le hash (si fourni)
        if ($receivedHash) {
            $isValid = $this->validator->validateHash(
                $sanitizedName,
                $data->getScore(),
                $data->getDuration(),
                $receivedHash
            );

            if (!$isValid) {
                $this->logger->error('Invalid score hash', [
                    'playerName' => $sanitizedName,
                    'score' => $data->getScore(),
                    'duration' => $data->getDuration(),
                    'receivedHash' => $receivedHash,
                    'ip' => $request->getClientIp()
                ]);
                throw new UnauthorizedHttpException('', 'Invalid score hash - possible tampering detected');
            }

            $this->logger->info('Score validated successfully', [
                'playerName' => $sanitizedName,
                'score' => $data->getScore()
            ]);
        }

        // 5. Définir la date de jeu si non fournie avec le fuseau horaire Europe/Paris
        if (!$data->getPlayedAt()) {
            $timezone = new \DateTimeZone('Europe/Paris');
            $data->setPlayedAt(new \DateTime('now', $timezone));
        }

        // 6. Persister en base de données
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}

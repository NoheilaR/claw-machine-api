<?php

namespace App\Service;

class ScoreValidator
{
    private string $secretKey;

    public function __construct()
    {
        // Récupérer depuis .env
        $this->secretKey = $_ENV['GAME_SECRET_KEY'] ?? 'CHANGE_ME_IN_PRODUCTION_12345';
    }

    /**
     * Génère un hash SHA256 pour valider l'intégrité des données
     */
    public function generateHash(string $playerName, int $score, float $duration): string
    {
        $data = "{$playerName}|{$score}|" . number_format($duration, 2, '.', '') . "|{$this->secretKey}";
        return hash('sha256', $data);
    }

    /**
     * Valide le hash reçu depuis Unity
     */
    public function validateHash(string $playerName, int $score, float $duration, string $receivedHash): bool
    {
        $expectedHash = $this->generateHash($playerName, $score, $duration);
        return hash_equals($expectedHash, $receivedHash);
    }

    /**
     * Sanitise le nom du joueur
     */
    public function sanitizePlayerName(string $name): string
    {
        // Supprimer les espaces en début/fin
        $name = trim($name);

        // Limiter la longueur
        if (strlen($name) > 20) {
            $name = substr($name, 0, 20);
        }

        // Ne garder que alphanumériques, espaces, - et _
        $name = preg_replace('/[^a-zA-Z0-9 _-]/', '', $name);

        // Si vide après sanitisation, retourner un nom par défaut
        return empty($name) ? 'Player' : $name;
    }

    /**
     * Vérifie si un score est plausible
     */
    public function isScorePlausible(int $score, float $duration, int $maxPointsPerSecond = 100): bool
    {
        // Score négatif = suspect
        if ($score < 0) {
            return false;
        }

        // Durée invalide = suspect
        if ($duration <= 0) {
            return false;
        }

        // Score trop élevé par rapport à la durée = suspect
        $maxPossibleScore = $duration * $maxPointsPerSecond;
        if ($score > $maxPossibleScore) {
            return false;
        }

        return true;
    }
}

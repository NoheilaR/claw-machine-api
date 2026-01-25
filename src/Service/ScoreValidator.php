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
        // Supprimer les espaces en debut/fin
        $name = trim($name);

        // Limiter la longueur (en caracteres, pas en bytes pour UTF-8)
        if (mb_strlen($name, 'UTF-8') > 20) {
            $name = mb_substr($name, 0, 20, 'UTF-8');
        }

        // Ne garder que alphanumeriques (avec accents), espaces, - et _
        // \p{L} = toutes les lettres Unicode (avec accents)
        // \p{N} = tous les chiffres Unicode
        $name = preg_replace('/[^\p{L}\p{N} _-]/u', '', $name);

        // Si vide apres sanitisation, retourner un nom par defaut
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

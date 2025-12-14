<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateScoreDto
{
    #[Assert\NotBlank(message: "Le nom du joueur est requis")]
    #[Assert\Length(
        min: 1,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractère",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9_\- ]+$/',
        message: "Le nom ne peut contenir que des lettres, chiffres, espaces, tirets et underscores"
    )]
    public ?string $playerName = null;

    #[Assert\NotNull(message: "Le score est requis")]
    #[Assert\Type(type: "integer", message: "Le score doit être un nombre entier")]
    #[Assert\PositiveOrZero(message: "Le score doit être positif ou zéro")]
    #[Assert\LessThanOrEqual(value: 999999, message: "Le score ne peut pas dépasser {{ compared_value }}")]
    public ?int $score = null;

    #[Assert\NotNull(message: "La durée est requise")]
    #[Assert\Type(type: "float", message: "La durée doit être un nombre décimal")]
    #[Assert\Positive(message: "La durée doit être positive")]
    #[Assert\LessThanOrEqual(value: 3600, message: "La durée ne peut pas dépasser {{ compared_value }} secondes")]
    public ?float $duration = null;

    /**
     * Hash de sécurité envoyé par Unity pour validation
     */
    public ?string $hash = null;

    /**
     * Timestamp de la partie (optionnel, sera généré côté serveur si absent)
     */
    public ?string $playedAt = null;
}

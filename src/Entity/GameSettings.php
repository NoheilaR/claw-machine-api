<?php

namespace App\Entity;

use App\Repository\GameSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;


#[ApiResource]
#[ORM\Entity(repositoryClass: GameSettingsRepository::class)]
class GameSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $timeLimit = null;

    // ===== TOKENS - ENERGY =====
    #[ORM\Column]
    private float $energyTokenProbability = 10.0;

    #[ORM\Column]
    private int $minEnergyTokens = 1;

    #[ORM\Column]
    private int $maxEnergyTokens = 3;

    // ===== TOKENS - BOMB =====
    #[ORM\Column]
    private float $bombTokenProbability = 5.0;

    #[ORM\Column]
    private int $minBombTokens = 0;

    #[ORM\Column]
    private int $maxBombTokens = 2;

    // ===== TOKENS - BLACKOUT =====
    #[ORM\Column]
    private float $blackoutTokenProbability = 3.0;

    #[ORM\Column]
    private int $minBlackoutTokens = 0;

    #[ORM\Column]
    private int $maxBlackoutTokens = 1;

    // ===== TOKENS - STUN =====
    #[ORM\Column]
    private float $stunTokenProbability = 5.0;

    #[ORM\Column]
    private int $minStunTokens = 0;

    #[ORM\Column]
    private int $maxStunTokens = 2;

    #[ORM\Column]
    private float $stunDuration = 2.0;

    // ===== PELUCHES - QUANTITÉS =====
    #[ORM\Column]
    private int $initialPlushieCount = 15;

    #[ORM\Column]
    private int $minPlushiesBeforeRespawn = 3;

    #[ORM\Column]
    private int $maxPlushiesInBin = 20;

    #[ORM\Column]
    private int $plushiesPerSpawn = 8;

    // ===== PELUCHES - RARETÉS =====
    #[ORM\Column]
    private float $commonProbability = 70.0;

    #[ORM\Column]
    private float $rareProbability = 25.0;

    // ===== PERTURBATION COMMANDES (GLITCH) =====
    #[ORM\Column]
    private float $glitchProbability = 60.0;

    #[ORM\Column]
    private float $glitchEnergyThreshold = 20.0;

    #[ORM\Column]
    private float $glitchChangeInterval = 3.0;

    // ===== GETTERS & SETTERS =====

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeLimit(): ?int
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(int $timeLimit): static
    {
        $this->timeLimit = $timeLimit;
        return $this;
    }

    // --- Energy Tokens ---
    public function getEnergyTokenProbability(): float
    {
        return $this->energyTokenProbability;
    }

    public function setEnergyTokenProbability(float $energyTokenProbability): static
    {
        $this->energyTokenProbability = $energyTokenProbability;
        return $this;
    }

    public function getMinEnergyTokens(): int
    {
        return $this->minEnergyTokens;
    }

    public function setMinEnergyTokens(int $minEnergyTokens): static
    {
        $this->minEnergyTokens = $minEnergyTokens;
        return $this;
    }

    public function getMaxEnergyTokens(): int
    {
        return $this->maxEnergyTokens;
    }

    public function setMaxEnergyTokens(int $maxEnergyTokens): static
    {
        $this->maxEnergyTokens = $maxEnergyTokens;
        return $this;
    }

    // --- Bomb Tokens ---
    public function getBombTokenProbability(): float
    {
        return $this->bombTokenProbability;
    }

    public function setBombTokenProbability(float $bombTokenProbability): static
    {
        $this->bombTokenProbability = $bombTokenProbability;
        return $this;
    }

    public function getMinBombTokens(): int
    {
        return $this->minBombTokens;
    }

    public function setMinBombTokens(int $minBombTokens): static
    {
        $this->minBombTokens = $minBombTokens;
        return $this;
    }

    public function getMaxBombTokens(): int
    {
        return $this->maxBombTokens;
    }

    public function setMaxBombTokens(int $maxBombTokens): static
    {
        $this->maxBombTokens = $maxBombTokens;
        return $this;
    }

    // --- Blackout Tokens ---
    public function getBlackoutTokenProbability(): float
    {
        return $this->blackoutTokenProbability;
    }

    public function setBlackoutTokenProbability(float $blackoutTokenProbability): static
    {
        $this->blackoutTokenProbability = $blackoutTokenProbability;
        return $this;
    }

    public function getMinBlackoutTokens(): int
    {
        return $this->minBlackoutTokens;
    }

    public function setMinBlackoutTokens(int $minBlackoutTokens): static
    {
        $this->minBlackoutTokens = $minBlackoutTokens;
        return $this;
    }

    public function getMaxBlackoutTokens(): int
    {
        return $this->maxBlackoutTokens;
    }

    public function setMaxBlackoutTokens(int $maxBlackoutTokens): static
    {
        $this->maxBlackoutTokens = $maxBlackoutTokens;
        return $this;
    }

    // --- Stun Tokens ---
    public function getStunTokenProbability(): float
    {
        return $this->stunTokenProbability;
    }

    public function setStunTokenProbability(float $stunTokenProbability): static
    {
        $this->stunTokenProbability = $stunTokenProbability;
        return $this;
    }

    public function getMinStunTokens(): int
    {
        return $this->minStunTokens;
    }

    public function setMinStunTokens(int $minStunTokens): static
    {
        $this->minStunTokens = $minStunTokens;
        return $this;
    }

    public function getMaxStunTokens(): int
    {
        return $this->maxStunTokens;
    }

    public function setMaxStunTokens(int $maxStunTokens): static
    {
        $this->maxStunTokens = $maxStunTokens;
        return $this;
    }

    public function getStunDuration(): float
    {
        return $this->stunDuration;
    }

    public function setStunDuration(float $stunDuration): static
    {
        $this->stunDuration = $stunDuration;
        return $this;
    }

    // --- Plushies Quantities ---
    public function getInitialPlushieCount(): int
    {
        return $this->initialPlushieCount;
    }

    public function setInitialPlushieCount(int $initialPlushieCount): static
    {
        $this->initialPlushieCount = $initialPlushieCount;
        return $this;
    }

    public function getMinPlushiesBeforeRespawn(): int
    {
        return $this->minPlushiesBeforeRespawn;
    }

    public function setMinPlushiesBeforeRespawn(int $minPlushiesBeforeRespawn): static
    {
        $this->minPlushiesBeforeRespawn = $minPlushiesBeforeRespawn;
        return $this;
    }

    public function getMaxPlushiesInBin(): int
    {
        return $this->maxPlushiesInBin;
    }

    public function setMaxPlushiesInBin(int $maxPlushiesInBin): static
    {
        $this->maxPlushiesInBin = $maxPlushiesInBin;
        return $this;
    }

    public function getPlushiesPerSpawn(): int
    {
        return $this->plushiesPerSpawn;
    }

    public function setPlushiesPerSpawn(int $plushiesPerSpawn): static
    {
        $this->plushiesPerSpawn = $plushiesPerSpawn;
        return $this;
    }

    // --- Plushies Rarities ---
    public function getCommonProbability(): float
    {
        return $this->commonProbability;
    }

    public function setCommonProbability(float $commonProbability): static
    {
        $this->commonProbability = $commonProbability;
        return $this;
    }

    public function getRareProbability(): float
    {
        return $this->rareProbability;
    }

    public function setRareProbability(float $rareProbability): static
    {
        $this->rareProbability = $rareProbability;
        return $this;
    }

    // --- Glitch (Perturbation Commandes) ---
    public function getGlitchProbability(): float
    {
        return $this->glitchProbability;
    }

    public function setGlitchProbability(float $glitchProbability): static
    {
        $this->glitchProbability = $glitchProbability;
        return $this;
    }

    public function getGlitchEnergyThreshold(): float
    {
        return $this->glitchEnergyThreshold;
    }

    public function setGlitchEnergyThreshold(float $glitchEnergyThreshold): static
    {
        $this->glitchEnergyThreshold = $glitchEnergyThreshold;
        return $this;
    }

    public function getGlitchChangeInterval(): float
    {
        return $this->glitchChangeInterval;
    }

    public function setGlitchChangeInterval(float $glitchChangeInterval): static
    {
        $this->glitchChangeInterval = $glitchChangeInterval;
        return $this;
    }
}

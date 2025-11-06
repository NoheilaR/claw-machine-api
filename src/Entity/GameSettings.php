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
    private ?float $clawSpeed = null;

    #[ORM\Column]
    private ?int $timeLimit = null;

    #[ORM\Column(length: 50)]
    private ?string $difficulty = null;

    #[ORM\Column]
    private ?float $itemSpawnRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClawSpeed(): ?float
    {
        return $this->clawSpeed;
    }

    public function setClawSpeed(float $clawSpeed): static
    {
        $this->clawSpeed = $clawSpeed;

        return $this;
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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getItemSpawnRate(): ?float
    {
        return $this->itemSpawnRate;
    }

    public function setItemSpawnRate(float $itemSpawnRate): static
    {
        $this->itemSpawnRate = $itemSpawnRate;

        return $this;
    }
}

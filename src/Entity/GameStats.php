<?php

namespace App\Entity;

use App\Repository\GameStatsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
#[ApiResource]
#[ORM\Entity(repositoryClass: GameStatsRepository::class)]
class GameStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalGames = null;

    #[ORM\Column]
    private ?float $averageDuration = null;

    #[ORM\Column]
    private ?int $highestScore = null;

    #[ORM\Column]
    private ?\DateTime $lastUpdated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalGames(): ?int
    {
        return $this->totalGames;
    }

    public function setTotalGames(int $totalGames): static
    {
        $this->totalGames = $totalGames;

        return $this;
    }

    public function getAverageDuration(): ?float
    {
        return $this->averageDuration;
    }

    public function setAverageDuration(float $averageDuration): static
    {
        $this->averageDuration = $averageDuration;

        return $this;
    }

    public function getHighestScore(): ?int
    {
        return $this->highestScore;
    }

    public function setHighestScore(int $highestScore): static
    {
        $this->highestScore = $highestScore;

        return $this;
    }

    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTime $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $playerName = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column]
    private ?\DateTime $playedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): static
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = round($duration, 2);
        return $this;
    }


    public function getPlayedAt(): ?\DateTime
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTime $playedAt): static
    {
        $this->playedAt = $playedAt;

        return $this;
    }
}

<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;

class Game
{
    private string $homeTeam;
    private string $awayTeam;
    private int $homeScore = 0;
    private int $awayScore = 0;
    private DateTime $addedAt;

    public function __construct(string $homeTeam, string $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->addedAt = new DateTime();
    }

    public function getHomeTeam(): string { return $this->homeTeam; }
    public function getAwayTeam(): string { return $this->awayTeam; }
    public function getHomeScore(): int { return $this->homeScore; }
    public function setHomeScore(int $homeScore): void {
        if ($homeScore < 0) throw new \InvalidArgumentException('Score cannot be negative.');
        $this->homeScore = $homeScore;
    }
    public function getAwayScore(): int { return $this->awayScore; }
    public function setAwayScore(int $awayScore): void {
        if ($awayScore < 0) throw new \InvalidArgumentException('Score cannot be negative.');
        $this->awayScore = $awayScore;
    }
    public function getTotalScore(): int { return $this->homeScore + $this->awayScore; }
    public function getAddedAt(): DateTime { return $this->addedAt; }
    public function getMatchScore(): string {
        return sprintf('%s - %s: %d - %d', $this->homeTeam, $this->awayTeam, $this->homeScore, $this->awayScore);
    }
    public function __toString(): string {
        return sprintf('%s %d - %s %d', $this->homeTeam, $this->homeScore, $this->awayTeam, $this->awayScore);
    }
}

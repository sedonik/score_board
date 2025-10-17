<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Game;
use App\Service\ScoreBoard\RepositoryInterface;
use InvalidArgumentException;

class ScoreBoard
{
    private RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    private function getKey(string $homeTeam, string $awayTeam): string
    {
        return strtolower($homeTeam . ' vs ' . $awayTeam);
    }

    public function startGame(string $homeTeam, string $awayTeam): void
    {
        if (empty($homeTeam) || empty($awayTeam)) {
            throw new InvalidArgumentException('Team names cannot be empty.');
        }
        if ($homeTeam === $awayTeam) {
            throw new InvalidArgumentException('Home and away teams must be different.');
        }
        if ($this->repository->findGame($homeTeam, $awayTeam) !== null) {
            throw new InvalidArgumentException('Game already exists.');
        }
        $this->repository->saveGame(new Game($homeTeam, $awayTeam));
    }

    public function updateScore(string $homeTeam, string $awayTeam, int $homeScore, int $awayScore): void
    {
        $game = $this->repository->findGame($homeTeam, $awayTeam);
        if ($game === null) {
            throw new InvalidArgumentException('Game not found.');
        }
        $game->setHomeScore($homeScore);
        $game->setAwayScore($awayScore);
        $this->repository->saveGame($game);
    }

    public function finishGame(string $homeTeam, string $awayTeam): void
    {
        $this->repository->deleteGame($homeTeam, $awayTeam);
    }

    /** @return Game[] */
    public function getGames(): array
    {
        return $this->repository->getAllGames();
    }

    /** @return Game[] */
    public function getSummary(): array
    {
        $games = $this->repository->getAllGames();
        usort($games, function (Game $a, Game $b): int {
            $totalComparison = $b->getTotalScore() <=> $a->getTotalScore();
            if ($totalComparison !== 0) return $totalComparison;
            return $b->getAddedAt() <=> $a->getAddedAt();
        });
        return $games;
    }
}

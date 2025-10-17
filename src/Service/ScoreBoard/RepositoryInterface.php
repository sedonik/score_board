<?php declare(strict_types=1);

namespace App\Service\ScoreBoard;

use App\Entity\Game;

interface RepositoryInterface
{
    public function findGame(string $homeTeam, string $awayTeam): ?Game;
    public function saveGame(Game $game): void;
    public function deleteGame(string $homeTeam, string $awayTeam): void;
    public function getAllGames(): array;
}

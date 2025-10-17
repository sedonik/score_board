<?php declare(strict_types=1);

namespace App\Service\ScoreBoard;

use App\Entity\Game;
use InvalidArgumentException;

class JsonRepository implements RepositoryInterface
{
    private const FILE_PATH = __DIR__ . '/../../../var/score_board.json';

    public function __construct()
    {
        if (!file_exists(dirname(self::FILE_PATH))) {
            mkdir(dirname(self::FILE_PATH), 0777, true);
        }
        if (!file_exists(self::FILE_PATH)) {
            file_put_contents(self::FILE_PATH, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    private function getKey(string $homeTeam, string $awayTeam): string
    {
        return strtolower($homeTeam . '_vs_' . $awayTeam);
    }

    private function loadGames(): array
    {
        $content = file_get_contents(self::FILE_PATH);
        $gamesData = json_decode($content, true) ?: [];
        return $gamesData;
    }

    private function saveGames(array $gamesData): void
    {
        file_put_contents(self::FILE_PATH, json_encode($gamesData, JSON_PRETTY_PRINT));
    }

    public function findGame(string $homeTeam, string $awayTeam): ?Game
    {
        $gamesData = $this->loadGames();
        $key = $this->getKey($homeTeam, $awayTeam);
        if (isset($gamesData[$key])) {
            $gameData = $gamesData[$key];
            $game = new Game($gameData['homeTeam'], $gameData['awayTeam']);
            $game->setHomeScore($gameData['homeScore']);
            $game->setAwayScore($gameData['awayScore']);
            return $game;
        }
        return null;
    }

    public function saveGame(Game $game): void
    {
        $gamesData = $this->loadGames();
        $key = $this->getKey($game->getHomeTeam(), $game->getAwayTeam());
        $gamesData[$key] = [
            'homeTeam' => $game->getHomeTeam(),
            'awayTeam' => $game->getAwayTeam(),
            'homeScore' => $game->getHomeScore(),
            'awayScore' => $game->getAwayScore(),
        ];
        $this->saveGames($gamesData);
    }

    public function deleteGame(string $homeTeam, string $awayTeam): void
    {
        $gamesData = $this->loadGames();
        $key = $this->getKey($homeTeam, $awayTeam);
        if (isset($gamesData[$key])) {
            unset($gamesData[$key]);
            $this->saveGames($gamesData);
        } else {
            throw new InvalidArgumentException('Game not found.');
        }
    }

    public function getAllGames(): array
    {
        $gamesData = $this->loadGames();
        $games = [];
        foreach ($gamesData as $key => $gameData) {
            $game = new Game($gameData['homeTeam'], $gameData['awayTeam']);
            $game->setHomeScore($gameData['homeScore']);
            $game->setAwayScore($gameData['awayScore']);
            $games[$key] = $game;
        }
        return array_values($games);
    }
}

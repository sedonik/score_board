<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Game;
use DateTime;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testConstructorWithValidParameters(): void
    {
        $homeTeam = 'Mexico';
        $awayTeam = 'Canada';

        $game = new Game($homeTeam, $awayTeam);

        $this->assertEquals($homeTeam, $game->getHomeTeam());
        $this->assertEquals($awayTeam, $game->getAwayTeam());
        $this->assertEquals(0, $game->getHomeScore());
        $this->assertEquals(0, $game->getAwayScore());
        $this->assertInstanceOf(DateTime::class, $game->getAddedAt());
    }

    public function testGetters(): void
    {
        $game = new Game('Brazil', 'Argentina');

        $this->assertEquals('Brazil', $game->getHomeTeam());
        $this->assertEquals('Argentina', $game->getAwayTeam());
        $this->assertEquals(0, $game->getHomeScore());
        $this->assertEquals(0, $game->getAwayScore());
        $this->assertInstanceOf(DateTime::class, $game->getAddedAt());
    }

    public function testSetHomeScoreWithValidScore(): void
    {
        $game = new Game('Spain', 'France');

        $game->setHomeScore(3);
        $this->assertEquals(3, $game->getHomeScore());

        $game->setHomeScore(0);
        $this->assertEquals(0, $game->getHomeScore());
    }

    public function testSetAwayScoreWithValidScore(): void
    {
        $game = new Game('Germany', 'Italy');

        $game->setAwayScore(2);
        $this->assertEquals(2, $game->getAwayScore());

        $game->setAwayScore(0);
        $this->assertEquals(0, $game->getAwayScore());
    }

    public function testSetHomeScoreWithNegativeScoreThrowsException(): void
    {
        $game = new Game('England', 'Scotland');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Score cannot be negative.');

        $game->setHomeScore(-1);
    }

    public function testSetAwayScoreWithNegativeScoreThrowsException(): void
    {
        $game = new Game('Portugal', 'Netherlands');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Score cannot be negative.');

        $game->setAwayScore(-5);
    }

    public function testGetTotalScore(): void
    {
        $game = new Game('Belgium', 'Croatia');

        $this->assertEquals(0, $game->getTotalScore());

        $game->setHomeScore(2);
        $this->assertEquals(2, $game->getTotalScore());

        $game->setAwayScore(1);
        $this->assertEquals(3, $game->getTotalScore());

        $game->setHomeScore(5);
        $game->setAwayScore(3);
        $this->assertEquals(8, $game->getTotalScore());
    }

    public function testGetMatchScore(): void
    {
        $game = new Game('Uruguay', 'Chile');

        $expected = 'Uruguay - Chile: 0 - 0';
        $this->assertEquals($expected, $game->getMatchScore());

        $game->setHomeScore(2);
        $game->setAwayScore(1);
        $expected = 'Uruguay - Chile: 2 - 1';
        $this->assertEquals($expected, $game->getMatchScore());
    }

    public function testToString(): void
    {
        $game = new Game('Colombia', 'Ecuador');

        $expected = 'Colombia 0 - Ecuador 0';
        $this->assertEquals($expected, (string) $game);

        $game->setHomeScore(3);
        $game->setAwayScore(2);
        $expected = 'Colombia 3 - Ecuador 2';
        $this->assertEquals($expected, (string) $game);
    }

    public function testAddedAtIsSetOnConstruction(): void
    {
        $beforeCreation = new DateTime();

        $game = new Game('Japan', 'South Korea');

        $afterCreation = new DateTime();

        $addedAt = $game->getAddedAt();

        $this->assertGreaterThanOrEqual($beforeCreation, $addedAt);
        $this->assertLessThanOrEqual($afterCreation, $addedAt);
    }

    public function testGameWithDifferentTeamNames(): void
    {
        $game1 = new Game('Team A', 'Team B');
        $game2 = new Game('Real Madrid', 'Barcelona');
        $game3 = new Game('Manchester United', 'Liverpool');

        $this->assertEquals('Team A', $game1->getHomeTeam());
        $this->assertEquals('Team B', $game1->getAwayTeam());

        $this->assertEquals('Real Madrid', $game2->getHomeTeam());
        $this->assertEquals('Barcelona', $game2->getAwayTeam());

        $this->assertEquals('Manchester United', $game3->getHomeTeam());
        $this->assertEquals('Liverpool', $game3->getAwayTeam());
    }

    public function testScoreUpdatesDoNotAffectOtherProperties(): void
    {
        $game = new Game('Chelsea', 'Arsenal');
        $originalHomeTeam = $game->getHomeTeam();
        $originalAwayTeam = $game->getAwayTeam();
        $originalAddedAt = $game->getAddedAt();

        $game->setHomeScore(4);
        $game->setAwayScore(2);

        $this->assertEquals($originalHomeTeam, $game->getHomeTeam());
        $this->assertEquals($originalAwayTeam, $game->getAwayTeam());
        $this->assertEquals($originalAddedAt, $game->getAddedAt());
    }
}

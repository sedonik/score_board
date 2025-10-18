<?php declare(strict_types=1);

namespace App\Tests\Command\ScoreBoard;

use App\Command\ScoreBoard\Start as StartCommand;
use App\Service\ScoreBoard;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Tester\CommandTester;

class StartCommandTest extends TestCase
{
    use ProphecyTrait;

    private CommandTester $commandTester;
    private ScoreBoard $scoreBoardMock;

    protected function setUp(): void
    {
        $prophecy = $this->prophesize(ScoreBoard::class);
        $this->scoreBoardMock = $prophecy->reveal();

        $command = new StartCommand($this->scoreBoardMock);
        $this->commandTester = new CommandTester($command);
    }

    public function testSuccessfulGameStart(): void
    {
        $this->scoreBoardMock->startGame('Mexico', 'Canada');

        $this->commandTester->execute([
            'home-team' => 'Mexico',
            'away-team' => 'Canada',
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Game started: Mexico vs Canada', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }
}

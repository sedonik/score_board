<?php declare(strict_types=1);

namespace App\Tests\Command\ScoreBoard;

use App\Command\ScoreBoard\Update as UpdateCommand;
use App\Service\ScoreBoard;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateCommandTest extends TestCase
{
    use ProphecyTrait;

    private CommandTester $commandTester;
    private ScoreBoard $scoreBoardMock;

    protected function setUp(): void
    {
        $prophecy = $this->prophesize(ScoreBoard::class);
        $this->scoreBoardMock = $prophecy->reveal();
        $command = new UpdateCommand($this->scoreBoardMock);
        $this->commandTester = new CommandTester($command);
    }

    public function testSuccessfulScoreUpdate(): void
    {
        $prophecy = $this->prophesize(ScoreBoard::class);
        $scoreBoardMock = $prophecy->reveal();

        $prophecy->updateScore('Mexico', 'Canada', 1, 2)->shouldBeCalledOnce();

        $command = new UpdateCommand($scoreBoardMock);
        $this->commandTester = new CommandTester($command);

        $this->commandTester->execute([
            'home-team' => 'Mexico',
            'away-team' => 'Canada',
            'home-score' => 1,
            'away-score' => 2,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Score updated: Mexico 1 - Canada 2', $output);
        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }
}

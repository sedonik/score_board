<?php declare(strict_types=1);

namespace App\Command\ScoreBoard;

use App\Service\ScoreBoard;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:scoreboard-matches')]
class Matches extends Command
{
    private ScoreBoard $scoreBoard;

    public function __construct(ScoreBoard $scoreBoard)
    {
        parent::__construct();
        $this->scoreBoard = $scoreBoard;
    }

    protected function configure(): void
    {
        $this->setDescription('Show detailed information about all current games.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $games = $this->scoreBoard->getGames();

        if (empty($games)) {
            $io->note('No games are currently active in the scoreboard.');
            return Command::SUCCESS;
        }

        $io->title('Current Matches');
        foreach ($games as $index => $game) {
            $io->writeln(($index + 1) . '. ' . (string)$game->getMatchScore());
        }

        return Command::SUCCESS;
    }
}

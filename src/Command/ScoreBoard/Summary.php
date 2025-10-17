<?php declare(strict_types=1);

namespace App\Command\ScoreBoard;

use App\Service\ScoreBoard;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:scoreboard-summary')]
class Summary extends Command
{
    private ScoreBoard $scoreBoard;

    public function __construct(ScoreBoard $scoreBoard)
    {
        parent::__construct();
        $this->scoreBoard = $scoreBoard;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $summary = $this->scoreBoard->getSummary();

        if (empty($summary)) {
            $io->note('No games in the scoreboard.');
            return Command::SUCCESS;
        }

        $io->title('Scoreboard Summary');
        foreach ($summary as $index => $game) {
            $io->writeln(($index + 1) . '. ' . (string)$game);
        }

        return Command::SUCCESS;
    }
}

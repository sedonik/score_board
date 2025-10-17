<?php declare(strict_types=1);

namespace App\Command\ScoreBoard;

use App\Service\ScoreBoard;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:scoreboard-update')]
class Update extends Command
{
    private ScoreBoard $scoreBoard;

    public function __construct(ScoreBoard $scoreBoard)
    {
        parent::__construct();
        $this->scoreBoard = $scoreBoard;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('home-team', InputArgument::REQUIRED, 'Home team name')
            ->addArgument('away-team', InputArgument::REQUIRED, 'Away team name')
            ->addArgument('home-score', InputArgument::REQUIRED, 'Home team score')
            ->addArgument('away-score', InputArgument::REQUIRED, 'Away team score');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $homeTeam = $input->getArgument('home-team');
        $awayTeam = $input->getArgument('away-team');
        $homeScore = (int)$input->getArgument('home-score');
        $awayScore = (int)$input->getArgument('away-score');

        try {
            $this->scoreBoard->updateScore($homeTeam, $awayTeam, $homeScore, $awayScore);
            $io->success("Score updated: $homeTeam $homeScore - $awayTeam $awayScore");
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

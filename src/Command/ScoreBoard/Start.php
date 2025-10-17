<?php declare(strict_types=1);

namespace App\Command\ScoreBoard;

use App\Service\ScoreBoard;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:scoreboard-start')]
class Start extends Command
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
            ->addArgument('away-team', InputArgument::REQUIRED, 'Away team name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $homeTeam = $input->getArgument('home-team');
        $awayTeam = $input->getArgument('away-team');

        try {
            $this->scoreBoard->startGame($homeTeam, $awayTeam);
            $io->success("Game started: $homeTeam vs $awayTeam");
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\UserInterface\Console;

use App\UseCase\SiteCheckerUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check')]
class SiteCheckerCommand extends Command
{
    public function __construct(private SiteCheckerUseCase $checkerUseCase)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //        $sum = $input->getArgument(self::SUM);
        $this->checkerUseCase->handle();

        return self::SUCCESS;
    }
}

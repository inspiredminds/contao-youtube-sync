<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\Command;

use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends Command
{
    public function __construct(private readonly NewsYouTubeSync $newsYouTubeSync)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronises all configured news archives with YouTube.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ($this->newsYouTubeSync)();

        return Command::SUCCESS;
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync\Command;

use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends Command
{
    private $newsYouTubeSync;

    public function __construct(NewsYouTubeSync $newsYouTubeSync)
    {
        parent::__construct();
        $this->newsYouTubeSync = $newsYouTubeSync;
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronises all configured news archives with YouTube.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ($this->newsYouTubeSync)();

        return 0;
    }
}

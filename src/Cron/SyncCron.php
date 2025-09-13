<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\Cron;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Psr\Log\LoggerInterface;

#[AsCronJob('hourly')]
class SyncCron
{
    public function __construct(
        private readonly NewsYouTubeSync $newsYouTubeSync,
        private readonly LoggerInterface $contaoErrorLogger,
    ) {
    }

    public function __invoke(): void
    {
        try {
            ($this->newsYouTubeSync)();
        } catch (\Throwable $e) {
            $this->contaoErrorLogger->error('Error while synchronising news entries from YouTube: '.$e->getMessage());
        }
    }
}

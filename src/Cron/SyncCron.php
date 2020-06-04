<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync\Cron;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\ServiceAnnotation\CronJob;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Psr\Log\LoggerInterface;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * @CronJob("hourly")
 */
class SyncCron implements ServiceAnnotationInterface
{
    private $newsSync;
    private $logger;

    public function __construct(NewsYouTubeSync $newsSync, LoggerInterface $logger)
    {
        $this->newsSync = $newsSync;
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        try {
            ($this->newsSync)();
        } catch (\Throwable $e) {
            $this->logger->error('Error while synchronising news entries from YouTube: '.$e->getMessage(), [
                'contao' => new ContaoContext(__METHOD__, 'ERROR'),
            ]);
        }
    }
}

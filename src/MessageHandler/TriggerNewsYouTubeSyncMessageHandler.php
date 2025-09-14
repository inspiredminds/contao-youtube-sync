<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\MessageHandler;

use InspiredMinds\ContaoYouTubeSync\Message\TriggerNewsYouTubeSyncMessage;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: TriggerNewsYouTubeSyncMessage::class)]
class TriggerNewsYouTubeSyncMessageHandler
{
    public function __construct(private readonly NewsYouTubeSync $newsYouTubeSync)
    {
    }

    public function __invoke(): void
    {
        ($this->newsYouTubeSync)();
    }
}

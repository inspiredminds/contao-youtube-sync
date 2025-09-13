<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\Event;

use Contao\NewsModel;
use Google\Service\YouTube\PlaylistItem;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * This event is dispatched, whenever a YouTube video is imported as a news article.
 */
class NewsYouTubeSyncEvent extends Event
{
    /**
     * @var bool
     */
    private $discard = false;

    public function __construct(
        private readonly NewsModel $news,
        private readonly PlaylistItem $video,
    ) {
    }

    /**
     * Sets whether or not this news import should be discarded.
     */
    public function setDicard(bool $discard): self
    {
        $this->discard = $discard;

        return $this;
    }

    /**
     * Whether or not this news import should be discarded.
     */
    public function getDiscard(): bool
    {
        return $this->discard;
    }

    /**
     * The news item to be saved to the database.
     */
    public function getNews(): NewsModel
    {
        return $this->news;
    }

    /**
     * The YouTube video instance.
     */
    public function getVideo(): PlaylistItem
    {
        return $this->video;
    }
}

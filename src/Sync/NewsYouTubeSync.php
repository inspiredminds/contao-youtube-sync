<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync\Sync;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Slug\Slug;
use Contao\Dbafs;
use Contao\FilesModel;
use Contao\NewsArchiveModel;
use Contao\NewsModel;
use Doctrine\DBAL\Connection;
use InspiredMinds\ContaoYouTubeSync\Event\NewsYouTubeSyncEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class NewsYouTubeSync
{
    private $youtube;
    private $framework;
    private $contaoSlug;
    private $db;
    private $eventDispatcher;
    private $projectDir;

    public function __construct(\Google_Service_YouTube $youtube, ContaoFramework $framework, Slug $contaoSlug, Connection $db, EventDispatcherInterface $eventDispatcher, string $projectDir)
    {
        $this->youtube = $youtube;
        $this->framework = $framework;
        $this->contaoSlug = $contaoSlug;
        $this->db = $db;
        $this->eventDispatcher = $eventDispatcher;
        $this->projectDir = $projectDir;
    }

    public function __invoke(): void
    {
        $this->framework->initialize();

        $enabledNewsArchives = NewsArchiveModel::findBy([
            'enable_youtube_sync = 1',
            "youtube_playlist_id != ''",
        ], []);

        if (null === $enabledNewsArchives) {
            return;
        }

        /** @var NewsArchiveModel $newsArchive */
        foreach ($enabledNewsArchives as $newsArchive) {
            $this->syncNewsArchive($newsArchive);
        }
    }

    private function syncNewsArchive(NewsArchiveModel $newsArchive): void
    {
        $params = [
            'playlistId' => $newsArchive->youtube_playlist_id,
            'maxResults' => 50,
        ];

        do {
            $videos = $this->youtube->playlistItems->listPlaylistItems('snippet,contentDetails,status', $params);

            /** @var \Google_Service_YouTube_PlaylistItem $video */
            foreach ($videos->getItems() as $video) {
                $this->processVideo($newsArchive, $video);
            }

            if (null !== $videos->getNextPageToken()) {
                $params['pageToken'] = $videos->getNextPageToken();
            }
        } while (null !== $videos->getNextPageToken());
    }

    private function processVideo(NewsArchiveModel $newsArchive, \Google_Service_YouTube_PlaylistItem $video): void
    {
        if ('public' !== $video->getStatus()->getPrivacyStatus()) {
            return;
        }

        $details = $video->getContentDetails();

        $news = NewsModel::findOneBy([
            'youtube_id = ?',
            'pid = ?',
        ], [
            $details->getVideoId(),
            (int) $newsArchive->id,
        ]);

        $snippet = $video->getSnippet();

        if (null === $news) {
            $news = new NewsModel();

            $news->author = (int) $newsArchive->youtube_sync_author;
            $news->youtube_id = $details->getVideoId();
            $news->alias = $this->contaoSlug->generate($snippet->getTitle(), $newsArchive->jumpTo, function (string $alias): bool {
                return ((int) $this->db->fetchOne('SELECT COUNT(*) FROM tl_news WHERE alias = ?', [$alias])) > 0;
            });

            if ($newsArchive->youtube_sync_publish) {
                $news->published = '1';
            }
        } else {
            if (!$newsArchive->youtube_sync_update) {
                return;
            }
        }

        $news->tstamp = time();
        $news->headline = $snippet->getTitle();
        $news->pid = (int) $newsArchive->id;
        $news->date = strtotime($details->getVideoPublishedAt());
        $news->time = $news->date;
        $news->youtube_data = json_encode($video->toSimpleObject());

        if (!empty($description = trim((string) $snippet->getDescription()))) {
            // parse URLs (https://gist.github.com/jasny/2000705)
            $description = preg_replace('@(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])@i', '<a href="$0" target="_blank" rel="noopener">$0</a>', $description);
            $news->teaser = '<p>'.nl2br($description).'</p>';
        }

        if (null !== ($teaserImage = $this->downloadTeaserImage($newsArchive, $video))) {
            $news->addImage = '1';
            $news->singleSRC = $teaserImage->uuid;
        }

        $event = new NewsYouTubeSyncEvent($news, $video);
        $this->eventDispatcher->dispatch($event);

        if (!$event->getDiscard()) {
            $news->save();
        }
    }

    private function downloadTeaserImage(NewsArchiveModel $newsArchive, \Google_Service_YouTube_PlaylistItem $video): ?FilesModel
    {
        $thumbnails = $video->getSnippet()->getThumbnails()->toSimpleObject();
        $thumbnailUrl = null;
        $thumbnailRes = 0;

        foreach ($thumbnails as $thumbnail) {
            $res = $thumbnail->width * $thumbnail->height;

            if ($res > $thumbnailRes) {
                $thumbnailRes = $res;
                $thumbnailUrl = $thumbnail->url;
            }
        }

        if (null !== $thumbnailUrl && null !== ($targetDir = FilesModel::findByUuid($newsArchive->youtube_sync_dir))) {
            $fileInfo = new \SplFileInfo($thumbnailUrl);
            $videoId = $video->getContentDetails()->getVideoId();

            $downloadDir = $targetDir->path.'/'.strtolower(substr($videoId, 0, 1));
            $downloadPath = $downloadDir.'/'.$videoId.'_'.$fileInfo->getFilename();

            if (!file_exists($this->projectDir.'/'.$downloadDir)) {
                mkdir($this->projectDir.'/'.$downloadDir, 0777, true);
            }

            (new \GuzzleHttp\Client())->get($thumbnailUrl, ['sink' => $this->projectDir.'/'.$downloadPath]);

            return Dbafs::addResource($downloadPath);
        }

        return null;
    }
}

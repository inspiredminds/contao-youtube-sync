<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\FrontendTemplate;
use Contao\Module;

#[AsHook('parseArticles')]
class ParseArticlesListener
{
    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        if (!(($template->youtube_data && \is_string($template->youtube_data)) === false)) {
            try {
                $template->youtube_data = json_decode($template->youtube_data, null, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                $template->youtube_data = (object) [];
            }
        }
    }
}

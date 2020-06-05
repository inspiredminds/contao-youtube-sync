<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\FrontendTemplate;
use Contao\Module;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * @Hook("parseArticles")
 */
class ParseArticlesListener implements ServiceAnnotationInterface
{
    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        if (!empty($template->youtube_data && \is_string($template->youtube_data))) {
            $template->youtube_data = json_decode($template->youtube_data);
        }
    }
}

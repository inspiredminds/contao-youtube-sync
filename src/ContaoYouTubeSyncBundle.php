<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync;

use InspiredMinds\ContaoYouTubeSync\DependencyInjection\ContaoYouTubeSyncExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoYouTubeSyncBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ContaoYouTubeSyncExtension();
        }

        return $this->extension;
    }
}

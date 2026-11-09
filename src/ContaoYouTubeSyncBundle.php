<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync;

use InspiredMinds\ContaoYouTubeSync\DependencyInjection\ContaoYouTubeSyncExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ContaoYouTubeSyncBundle extends AbstractBundle
{
    public function getContainerExtension(): ExtensionInterface|null
    {
        $this->extension ??= new ContaoYouTubeSyncExtension();

        return $this->extension;
    }
}

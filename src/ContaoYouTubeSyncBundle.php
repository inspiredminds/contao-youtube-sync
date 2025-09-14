<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoYouTubeSync;

use InspiredMinds\ContaoYouTubeSync\DependencyInjection\ContaoYouTubeSyncExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ContaoYouTubeSyncBundle extends AbstractBundle
{
    public function getContainerExtension(): ExtensionInterface|null
    {
        if (null === $this->extension) {
            $this->extension = new ContaoYouTubeSyncExtension();
        }

        return $this->extension;
    }
}

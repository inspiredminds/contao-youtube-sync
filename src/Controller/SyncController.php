<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\Controller;

use Contao\CoreBundle\Controller\AbstractBackendController;
use Contao\CoreBundle\Framework\ContaoFramework;
use InspiredMinds\ContaoYouTubeSync\Message\TriggerNewsYouTubeSyncMessage;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[AsController]
#[Route('%contao.backend.route_prefix%/youtubesync', name: self::class, defaults: ['_scope' => 'backend'])]
class SyncController extends AbstractBackendController
{
    public function __construct(
        private readonly NewsYouTubeSync $newsYouTubeSync,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Environment $twig,
        private readonly TranslatorInterface $translator,
        private readonly MessageBusInterface $messageBus,
        private readonly ContaoFramework $contaoFramework,
    ) {
    }

    public function __invoke(): Response
    {
        $this->messageBus->dispatch(new TriggerNewsYouTubeSyncMessage());

        return $this->render('@ContaoYouTubeSync/sync.html.twig', ['form_route' => self::class]);
    }
}

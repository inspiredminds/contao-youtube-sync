<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\Controller;

use Contao\CoreBundle\Controller\AbstractBackendController;
use Contao\NewsModel;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[AsController]
#[Route('%contao.backend.route_prefix%/youtubesync', name: self::class, defaults: ['_scope' => 'backend', '_token_check' => false])]
class SyncController extends AbstractBackendController
{
    public function __construct(
        private readonly NewsYouTubeSync $newsYouTubeSync,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Environment $twig,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function __invoke(): Response
    {
        $count = (int) NewsModel::countAll();
        ($this->newsYouTubeSync)();
        $count = (int) NewsModel::countAll() - $count;

        return
            $this->render('@ContaoYouTubeSync/sync.html.twig', [
                'section_headline' => $this->translator->trans('contao_youtube_sync.backend.section_headline'),
                'submit_label' => $this->translator->trans('contao_youtube_sync.backend.submit_label'),
                'back' => $this->translator->trans('MSC.goBack', [], 'contao_default'),
                'form_action' => $this->urlGenerator->generate(self::class),
                'count' => $count,
                'count_label' => $this->translator->trans('contao_youtube_sync.backend.count_label', ['%count%' => $count]),
            ]);
    }
}

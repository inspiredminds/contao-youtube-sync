<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoYouTubeSync\Action;

use Contao\NewsModel;
use InspiredMinds\ContaoYouTubeSync\Sync\NewsYouTubeSync;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[Route('/contao/youtubesync', name: self::class, defaults: ['_scope' => 'backend', '_token_check' => false])]
class SyncAction
{
    private $newsYouTubeSync;
    private $router;
    private $twig;
    private $translator;

    public function __construct(NewsYouTubeSync $newsYouTubeSync, RouterInterface $router, Environment $twig, TranslatorInterface $translator)
    {
        $this->newsYouTubeSync = $newsYouTubeSync;
        $this->router = $router;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public function __invoke(): Response
    {
        $count = (int) NewsModel::countAll();
        ($this->newsYouTubeSync)();
        $count = (int) NewsModel::countAll() - $count;

        return new Response(
            $this->twig->render('@ContaoYouTubeSync/sync.html.twig', [
                'section_headline' => $this->translator->trans('contao_youtube_sync.backend.section_headline'),
                'submit_label' => $this->translator->trans('contao_youtube_sync.backend.submit_label'),
                'back' => $this->translator->trans('MSC.goBack', [], 'contao_default'),
                'form_action' => $this->router->generate(self::class),
                'count' => $count,
                'count_label' => $this->translator->trans('contao_youtube_sync.backend.count_label', ['%count%' => $count]),
            ])
        );
    }
}

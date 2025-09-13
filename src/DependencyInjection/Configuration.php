<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoYouTubeSync\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_youtube_sync');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('application_name')
                    ->info('Application name for the Google API.')
                    ->defaultValue('Contao YouTube Sync')
                ->end()
                ->scalarNode('developer_key')
                    ->info('Google Console API key.')
                    ->defaultValue('')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

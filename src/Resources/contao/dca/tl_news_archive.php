<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license proprietary
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['enable_youtube_sync'] = [
    'inputType' => 'checkbox',
    'exclude' => true,
    'eval' => ['submitOnChange' => true],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['youtube_channel_id'] = [
    'inputType' => 'text',
    'exclude' => true,
    'eval' => ['maxlength' => 64, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['youtube_sync_author'] = [
    'exclude' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_user.name',
    'eval' => ['chosen' => true, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['youtube_sync_publish'] = [
    'inputType' => 'checkbox',
    'exclude' => true,
    'eval' => ['tl_class' => 'clr w50'],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['youtube_sync_update'] = [
    'inputType' => 'checkbox',
    'exclude' => true,
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_news_archive']['fields']['youtube_sync_dir'] = [
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => ['tl_class' => 'clr', 'mandatory' => true, 'fieldType'=>'radio'],
	'sql' => ['type' => 'binary', 'length' => 16, 'notnull' => false],
];

PaletteManipulator::create()
    ->addLegend('youtube_sync_legend', null)
    ->addField('enable_youtube_sync', 'youtube_sync_legend')
    ->applyToPalette('default', 'tl_news_archive')
;

$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['__selector__'][] = 'enable_youtube_sync';
$GLOBALS['TL_DCA']['tl_news_archive']['subpalettes']['enable_youtube_sync'] = 'youtube_channel_id,youtube_sync_author,youtube_sync_publish,youtube_sync_update,youtube_sync_dir';

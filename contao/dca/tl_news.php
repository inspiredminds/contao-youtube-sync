<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * (c) INSPIRED MINDS
 */

$GLOBALS['TL_DCA']['tl_news']['fields']['youtube_id'] = [
    'inputType' => 'text',
    'eval' => ['readonly' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_news']['fields']['youtube_data'] = [
    'sql' => ['type' => 'blob', 'notnull' => false],
];

PaletteManipulator::create()
    ->addField('youtube_id', 'meta_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_news')
;

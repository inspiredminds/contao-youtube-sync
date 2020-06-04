<?php

declare(strict_types=1);

/*
 * This file is part of the Contao YouTube Sync extension.
 *
 * (c) inspiredminds
 *
 * @license proprietary
 */

$GLOBALS['TL_DCA']['tl_news']['fields']['youtube_id'] = [
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_news']['fielsd']['youtube_data'] = [
    'sql' => ['type' => 'blob', 'notnull' => false],
];

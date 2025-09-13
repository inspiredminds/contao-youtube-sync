<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

$GLOBALS['TL_DCA']['tl_news']['fields']['youtube_id'] = [
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_news']['fields']['youtube_data'] = [
    'sql' => ['type' => 'blob', 'notnull' => false],
];

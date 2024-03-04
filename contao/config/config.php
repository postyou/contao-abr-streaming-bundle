<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle
 *
 * (c) Postyou Werbeagentur
 *
 * @license MIT
 */

use Postyou\ContaoABRStreamingBundle\ContentAbrstreaming;

$GLOBALS['TL_CTE']['media']['abrstreaming'] = ContentAbrstreaming::class;

$GLOBALS['TL_MIME']['mpd'] = ['application/dash+xml', 'iconMPEG.svg'];
$GLOBALS['TL_MIME']['m3u8'] = ['application/vnd.apple.mpegurl', 'iconMPEG.svg'];

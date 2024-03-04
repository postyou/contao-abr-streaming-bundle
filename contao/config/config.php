<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle.
 *
 * (c) POSTYOU Werbeagentur
 *
 * @license MIT
 */

use Postyou\ContaoABRStreamingBundle\ContentVideoJsPlayer;

$GLOBALS['TL_CTE']['media']['player'] = ContentVideoJsPlayer::class;

$GLOBALS['TL_MIME']['mpd'] = ['application/dash+xml', 'iconMPEG.svg'];
$GLOBALS['TL_MIME']['m3u8'] = ['application/vnd.apple.mpegurl', 'iconMPEG.svg'];

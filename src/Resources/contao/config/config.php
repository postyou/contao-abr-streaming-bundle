<?php
/**
 *
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2018-2018 POSTYOU
 *
 * @package
 * @author  Markus Nestmann
 * @link    http://www.postyou.de
 */

$GLOBALS['TL_CTE']['media']['abrstreaming'] = 'Postyou\ContaoABRStreamingBundle\ContentAbrstreaming';
$GLOBALS['TL_MIME']['mpd'] = array('application/dash+xml', 'iconMPEG.svg');
$GLOBALS['TL_MIME']['m3u8'] = array('application/x-mpegURL', 'iconMPEG.svg');

$GLOBALS['TL_HOOKS']['parseTemplate'][] = array('Postyou\ContaoABRStreamingBundle\dashPlayerPlugin', 'myParseFrontendTemplate');
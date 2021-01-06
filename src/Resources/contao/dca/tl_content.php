<?php
/**
 *
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2018-2019 POSTYOU
 *
 * @package
 * @author  Markus Nestmann
 * @link    http://www.postyou.de
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

// Add palette to tl_content
$GLOBALS['TL_DCA']['tl_content']['palettes']['abrstreaming'] = '{type_legend},type,headline;{source_legend},abrs_playerSRC;{player_legend},playerSize,playerOptions,playerStart,playerStop,playerCaption;{poster_legend:hide},posterSRC;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

// Add fields to tl_content
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_playerSRC'] = array(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['abrs_playerSRC'],
    'exclude'			=> true,
    'inputType'			=> 'fileTree',
    'eval'				=> array('filesOnly'=>true, 'multiple'=>true, 'fieldType'=>'checkbox', 'extensions' =>'mpd, m3u8', 'mandatory'=>true, 'tl_class'=>'clr autoheight'),
    'sql'				=> "blob NULL"
);

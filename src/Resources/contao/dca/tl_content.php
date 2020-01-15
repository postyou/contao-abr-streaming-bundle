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
$GLOBALS['TL_DCA']['tl_content']['palettes']['abrstreaming'] = '{type_legend},type,headline;{sources},abrs_playerSRC;{poster_legend:hide},posterSRC;{player_legend},abrs_playerSize,abrs_autoplay;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

// Add fields to tl_content
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_playerSRC'] = array(
    'label'				=> &$GLOBALS['TL_LANG']['tl_content']['abrs_playerSRC'],
    'exclude'			=> true,
    'inputType'			=> 'fileTree',
    'eval'				=> array('filesOnly'=>true, 'multiple'=>true, 'fieldType'=>'checkbox', 'extensions' =>'mpd, m3u8, mp4, m4v, mov, wmv, webm, ogv', 'mandatory'=>true, 'tl_class'=>'clr autoheight'),
    'sql'				=> "blob NULL"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_playerSize'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['playerSize'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_autoplay'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['autoplay'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

<?php

// Add palette to tl_content
$GLOBALS['TL_DCA']['tl_content']['palettes']['abrstreaming'] = '{type_legend},type,headline;{sources},abrs_playerSRC,abrs_playerSize,abrs_autoplay';

// Add fields to tl_content
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_playerSRC'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_abrstreaming']['file'],
	'exclude'			=> true,
	'inputType'			=> 'fileTree',
	'eval'				=> array('helpwizard'=>true, 'filesOnly'=>true, 'multiple'=>true, 'fieldType'=>'checkbox', 'extensions' =>'mpd', 'mandatory'=>true, 'tl_class'=>'clr w50 autoheight'),
	'sql'				=> "blob NULL"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_playerSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['playerSize'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['abrs_autoplay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['autoplay'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
<?php
 
 
/**
 * Table tl_abrstreaming
 */
$GLOBALS['TL_DCA']['tl_abrstreaming'] = array
(
 
	// Config
	'config'   => array
	(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'sql'              => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
    ),
    // List
	'list'     => array
	(
		'sorting'           => array
		(
			'mode'        => 2,
			'fields'      => array('title'),
			'flag'        => 1,
			'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array
		(
			'fields' => array('title'),
			'format' => '%s',
        ),
        'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
        ),
        'operations'        => array
		(
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_abrstreaming']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_abrstreaming']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show'   => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_abrstreaming']['show'],
				'href'       => 'act=show',
				'icon'       => 'show.gif',
				'attributes' => 'style="margin-right:3px"'
			),
		)
    ),
    // Palettes
	'palettes' => array
	(
		'default'       => '{title_legend},type,title,{abrstreaming_legend},url'
),
// Fields
'fields'   => array
(
    'id'     => array
    (
        'sql' => "int(10) unsigned NOT NULL auto_increment"
    ),
    'tstamp' => array
    (
        'sql' => "int(10) unsigned NOT NULL default '0'"
    ),
    'title'  => array
    (
        'label'     => &$GLOBALS['TL_LANG']['tl_abrstreaming']['title'],
        'inputType' => 'text',
        'exclude'   => true,
        'sorting'   => true,
        'flag'      => 1,
                    'search'    => true,
        'eval'      => array(
            'mandatory'   => true,
                            'unique'         => true,
                            'maxlength'   => 255,
            'tl_class'        => 'w50',

        ),
        'sql'       => "varchar(255) NOT NULL default ''"
	),
	'upload'  => array
    (
		'label'				=> &$GLOBALS['TL_LANG']['tl_abrstreaming']['upload'],
		'exclude'			=> true,
		'inputType'			=> 'text',
		'eval'				=> array('helpwizard'=>true, 'filesOnly'=>true, 'fieldType'=>'radio', 'extensions' =>'mp4', 'mandatory'=>true, 'tl_class'=>'clr w50 autoheight'),
		'sql'				=> "varchar(255) NOT NULL default ''"
    )
   )
);
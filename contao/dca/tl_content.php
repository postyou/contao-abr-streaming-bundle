<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * This file is part of postyou/contao-abr-streaming-bundle
 *
 * (c) Postyou Werbeagentur
 *
 * @license MIT
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'useVideoJs';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['useVideoJs'] = 'videoJsSetup';


$GLOBALS['TL_DCA']['tl_content']['fields']['useVideoJs'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) COLLATE ascii_bin NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['videoJsSetup'] = [
    'inputType' => 'textarea',
    'default' => <<<'EOF'
        {
            "plugins": {
                "httpSourceSelector": {
                    "default": "auto"
                }
            }
        }
        EOF,
    'eval' => ['class' => 'monospace', 'rte' => 'ace|json', 'tl_class' => 'clr', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'text NULL',
];
        
PaletteManipulator::create()
    ->addLegend('videojs_legend', 'player_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('useVideoJs', 'videojs_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('player', 'tl_content')
;

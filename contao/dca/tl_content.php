<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle
 *
 * (c) Postyou Werbeagentur
 *
 * @license MIT
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['abrstreaming'] = '
    {type_legend},type,headline;
    {source_legend},playerSRC;
    {player_legend},playerOptions,playerSetup,playerSize,playerPreload,playerCaption,playerStart,playerStop;
    {poster_legend:hide},posterSRC;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['fields']['playerSetup'] = [
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

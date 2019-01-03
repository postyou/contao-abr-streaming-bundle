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
namespace Postyou\ContaoABRStreamingBundle;

use Contao\Input;
use Contao\ContentModel;
use Contao\Message;

class tl_content
{
    /**
     * Show a hint if a JavaScript library needs to be included in the page layout
     *
     * @param object
     */
    public function showJsLibraryHint($dc)
    {
        if ($_POST || Input::get('act') != 'edit') {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null) {
            return;
        }

        if ($objCte->type == 'abrstreaming') {
            Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_content']['includeTemplate'], 'js_mediaelement'));
        }
    }
}

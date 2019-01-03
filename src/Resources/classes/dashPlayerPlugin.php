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

class dashPlayerPlugin
{
    public function myParseFrontendTemplate($objTemplate)
    {
        if ($objTemplate->getName() == 'js_mediaelement') {
            $objTemplate->setName('js_mediaelement_dash');
        }
    }
}

<?php

namespace Postyou\ContaoABRStreamingBundle;

class dashPlayerPlugin
{
    public function myParseFrontendTemplate($objTemplate)
    {
        if ($objTemplate->getName() == 'js_mediaelement')
        {
            $objTemplate->setName('js_mediaelement_dash');
        }
    }
}
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

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PostyouContaoABRStreamingBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}

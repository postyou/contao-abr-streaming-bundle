<?php
/**
 *
 * Extension for Contao Open Source CMS (contao.org)
 *
 * Copyright (c) 2018-2018 POSTYOU
 *
 * @package
 * @author  Markus Nestmann
 * @link    http://www.postyou.de
 */
namespace Postyou\ABRStreamingBundle\ContaoManager;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
class Plugin implements BundlePluginInterface
{
  /**
   * Plugin for the Contao Manager.
   *
   * @author Markus Nestmann
   */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('Postyou\ABRStreamingBundle\PostyouABRStreamingBundle')
                            ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
        ];
    }
}
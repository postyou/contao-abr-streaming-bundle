<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle
 *
 * (c) Postyou Werbeagentur
 *
 * @license MIT
 */

namespace Postyou\ContaoABRStreamingBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Postyou\ContaoABRStreamingBundle\PostyouContaoABRStreamingBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(PostyouContaoABRStreamingBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}

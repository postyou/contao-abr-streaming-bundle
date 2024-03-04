<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle.
 *
 * (c) POSTYOU Werbeagentur
 *
 * @license MIT
 */

namespace Postyou\ContaoABRStreamingBundle\DependencyInjection;

use Contao\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class PostyouContaoABRStreamingExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $contaoConfig = new Configuration((string) $container->getParameter('kernel.project_dir'));

        $configs = $this->processConfiguration($contaoConfig, $container->getExtensionConfig('contao'));

        foreach (array_reverse($configs) as $key => $config) {
            if ('editable_files' !== $key) {
                continue;
            }

            $container->prependExtensionConfig('contao', [
                'editable_files' => $config.',mpd,m3u8',
            ]);
        }
    }
}

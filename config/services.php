<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle.
 *
 * (c) POSTYOU Werbeagentur
 *
 * @license MIT
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()

        ->load('Postyou\\ContaoABRStreamingBundle\\', '../src/')
            ->exclude('../src/{ContaoManager,DependencyInjection}')
    ;
};

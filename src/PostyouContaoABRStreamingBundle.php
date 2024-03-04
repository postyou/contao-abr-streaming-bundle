<?php

declare(strict_types=1);

/*
 * This file is part of postyou/contao-abr-streaming-bundle.
 *
 * (c) POSTYOU Werbeagentur
 *
 * @license MIT
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

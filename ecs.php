<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

$header = <<<'EOF'
    This file is part of postyou/contao-abr-streaming-bundle.

    (c) POSTYOU Werbeagentur

    @license MIT
    EOF;

return ECSConfig::configure()
    ->withSets([__DIR__.'/vendor/contao/easy-coding-standard/config/contao.php'])
    ->withPhpCsFixerSets(
        symfony: true,
        symfonyRisky: true,
        php82Migration: true,
        php80MigrationRisky: true
    )
    ->withConfiguredRule(HeaderCommentFixer::class, ['header' => $header])
    ->withConfiguredRule(MultilinePromotedPropertiesFixer::class, ['keep_blank_lines' => true])
    ->withRules([SingleLineEmptyBodyFixer::class])
    ->withSkip([
        MethodChainingIndentationFixer::class => [
            'config/*.php',
        ],
    ])
    ->withPaths([
        __DIR__.'/config',
        __DIR__.'/contao',
        __DIR__.'/src',
    ])
    ->withSpacing(Option::INDENTATION_SPACES, "\n")
    ->withParallel()
    ->withCache(sys_get_temp_dir().'/ecs_default_cache')
;

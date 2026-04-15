<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/Tests/Unit',
    ])
    ->withRootFiles()
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        naming: true,
    )
    ->withComposerBased(
        doctrine: true,
        phpunit: true,
        symfony: true,
    )
    ->withParallel(timeoutSeconds: 600)
    ->withCache(__DIR__ . '/var/cache/rector')
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/Tests/resources',
        __DIR__ . '/var',
    ]);

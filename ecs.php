<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\NoMixedEchoPrintFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\SpacesInsideParenthesesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withParallel()
    ->withCache(__DIR__ . '/var/cache/ecs')

    // Base standards instead of hundreds of explicit fixers
    ->withPreparedSets(psr12: true)
    ->withPhpCsFixerSets(
        perCS20: true,
        phpCsFixer: true,
    )

    // Only keep rules that express your team's actual preferences
    ->withConfiguredRule(TrailingCommaInMultilineFixer::class, [
        'elements' => ['arguments', 'parameters', 'arrays', 'match'],
        'after_heredoc' => true,
    ])
    ->withConfiguredRule(NoMixedEchoPrintFixer::class, [
        'use' => 'echo',
    ])
    ->withConfiguredRule(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ])
    ->withConfiguredRule(ClassDefinitionFixer::class, [
        'single_item_single_line' => true,
        'multi_line_extends_each_single_line' => true,
    ])
    ->withConfiguredRule(SingleLineCommentStyleFixer::class, [
        'comment_types' => ['hash'],
    ])
    ->withConfiguredRule(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ])
    ->withConfiguredRule(IncrementStyleFixer::class, [
        'style' => 'pre',
    ])
    ->withConfiguredRule(NoExtraBlankLinesFixer::class, [
        'tokens' => [
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
            'attribute',
        ],
    ])
    ->withConfiguredRule(PhpdocTypesOrderFixer::class, [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'none',
    ])
    ->withConfiguredRule(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_mixed' => true,
        'remove_inheritdoc' => true,
    ])
    ->withConfiguredRule(OrderedImportsFixer::class, [
        'sort_algorithm' => 'alpha',
        'imports_order' => ['class', 'function', 'const'],
    ])
    ->withConfiguredRule(GlobalNamespaceImportFixer::class, [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ])
    ->withConfiguredRule(FullyQualifiedStrictTypesFixer::class, [
        'import_symbols' => true,
    ])
    ->withConfiguredRule(NativeFunctionInvocationFixer::class, [
        'scope' => 'namespaced',
        'strict' => true,
        'include' => ['@compiler_optimized'],
    ])
    ->withConfiguredRule(TypesSpacesFixer::class, [
        'space' => 'none',
    ])
    ->withConfiguredRule(SpacesInsideParenthesesFixer::class, [
        'space' => 'none',
    ])
    ->withConfiguredRule(OrderedClassElementsFixer::class, [
        'order' => [
            'use_trait',
            'case',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public_static',
            'property_protected_static',
            'property_private_static',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public_static',
            'method_protected_static',
            'method_private_static',
            'method_public',
            'method_protected',
            'method_private',
        ],
        'sort_algorithm' => 'none',
    ])
    ->withConfiguredRule(PhpUnitStrictFixer::class, [
        'assertions' => [
            'assertAttributeEquals',
            'assertAttributeNotEquals',
            'assertEquals',
            'assertNotEquals',
        ],
    ])
    ->withSkip([
        '*/var/*',
        '*/vendor/*',
        '*/node_modules/*',
        '*/migrations/*',
    ]);

<?php

declare(strict_types=1);

return
    (new PhpCsFixer\Config())
        ->setCacheFile(__DIR__ . '/var/php_cs.cache')
        ->setFinder(
            PhpCsFixer\Finder::create()
                ->in([
                    __DIR__ . '/src',
                    __DIR__ . '/tests',
                ])
                ->append([
                    __FILE__,
                ])
        )
        ->setRules([
            '@PSR12' => true,
            '@PHP84Migration' => true,

            'no_unused_imports' => true,
            'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],

            'no_superfluous_phpdoc_tags' => ['remove_inheritdoc' => true],

            'phpdoc_types_order' => ['null_adjustment' => 'always_last'],

            'strict_comparison' => true,
            'strict_param' => true,

            'binary_operator_spaces' => true,

            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],

            'no_superfluous_elseif' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,

            'php_unit_internal_class' => true,
            'php_unit_construct' => true,
            'php_unit_fqcn_annotation' => true,
            'php_unit_set_up_tear_down_visibility' => true,

            'static_lambda' => true,

            'global_namespace_import' => true,
        ]);

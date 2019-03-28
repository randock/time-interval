<?php

declare(strict_types=1);
$finder = PhpCsFixer\Finder::create()
    ->in(getcwd())
    ->exclude('vendor')
    ->exclude('bin')
    ->exclude('web')

    // symfony 3+
    ->exclude('var')

    // symfony 4+
    ->exclude('node_modules')
    ->exclude('build')

    // symfony < 3
    ->exclude('app/cache')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,

        // php7.1+
        '@PHP71Migration' => true,

        // php7+
        'declare_strict_types' => true,

        // custom config
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp']],
        'heredoc_to_nowdoc' => true,
        'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_short_echo_tag' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'php_unit_strict' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'concat_space' => ['spacing' => 'one'],
        'native_function_invocation' => ['strict' => true]
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
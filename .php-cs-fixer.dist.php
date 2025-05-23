<?php
$finder = (new PhpCsFixer\Finder())->in(__DIR__)->exclude(['autogenerated_content']);
return (new PhpCsFixer\Config())
    ->setCacheFile(getcwd().'/.php_cs.cache')
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'yoda_style' => false,
        'fopen_flags' => false,
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                '@author'
            ]
        ],
        'global_namespace_import' => true,
        'array_syntax' => ['syntax' => 'short'],
        'native_constant_invocation' => ['fix_built_in' => false],
        'native_function_invocation' => ['include' => []],
        'no_unreachable_default_argument_value' => true,
        'ordered_imports' => [
            'imports_order' => [
                'class', 'function', 'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'self_accessor' => false,
        'visibility_required' => ['elements' => ['method', 'property']],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;

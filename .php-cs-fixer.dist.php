<?php

$finder = PhpCsFixer\Finder::create()->in([
    './src',
    './tests',
]);

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setCacheFile('var/cache/.php_cs.cache')
    ->setRules([
        '@PSR1' => true,
        '@PSR12' => true,
        'no_unused_imports' => true,
        'no_extra_blank_lines' => true,
        'no_empty_phpdoc' => true,
        'single_line_empty_body' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'remove_inheritdoc' => true,
        ],
    ]);

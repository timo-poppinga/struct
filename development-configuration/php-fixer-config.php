<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only. Please check your command.');
}

$finder = Finder::create()
    ->in([
        'src',
        'tests'
    ]);

$config = new Config();
return $config->setRules([
    '@PSR2' => true,
    'blank_line_after_opening_tag' => true,
    'no_leading_import_slash' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_unused_imports' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'no_whitespace_in_blank_line' => true,
    'ordered_imports' => true,
    'single_quote' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => true,
    'phpdoc_no_package' => true,
    'phpdoc_scalar' => true,
    'no_blank_lines_after_phpdoc' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'whitespace_after_comma_in_array' => true,
    'function_typehint_space' => true,
    'single_line_comment_style' => true,
    'no_alias_functions' => true,
    'lowercase_cast' => true,
    'no_leading_namespace_whitespace' => true,
    'native_function_casing' => true,
    'self_accessor' => true,
    'no_short_bool_cast' => true,
    'no_unneeded_control_parentheses' => true,
    'declare_equal_normalize' => [
        'space' => 'none'
    ],
    'single_trait_insert_per_statement' => true,
    'braces' => [
        'allow_single_line_closure' => true
    ],
    'compact_nullable_typehint' => true,
    'new_with_braces' => true,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline'
    ],
    'return_type_declaration' => [
        'space_before' => 'none'
    ],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);

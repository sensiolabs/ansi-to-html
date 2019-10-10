<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$header = <<<TXT
This file is part of ansi-to-html.

(c) 2013 Fabien Potencier

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
TXT;

$rules = [
    '@PSR2' => true,
    '@Symfony' => true,
    'cast_spaces' => [
        'space' => 'none',
    ],
    'concat_space' => [
        'spacing' => 'none',
    ],
    'native_function_invocation' => [
        'scope' => 'namespaced',
    ],
    'psr4' => true,
    'phpdoc_align' => [
        'align' => 'left',
    ],
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'header_comment' => [
        'header' => $header,
        'commentType' => PhpCsFixer\Fixer\Comment\HeaderCommentFixer::HEADER_PHPDOC,
    ],
    'yoda_style' => false,
];

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
    ->setCacheFile($cacheDir . '/.php_cs.cache');

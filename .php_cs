<?php
use PhpCsFixer\Config;
$header = <<<EOF
EOF;
$config = new Config();
$config->getFinder()
    ->files()
    ->in(__DIR__)
    ->exclude('vendor')
    ->name('*.php');
$config
    ->setRules(array(
        '@PSR2' => true,
        '@Symfony' => true,
        'header_comment' => array('header' => $header),
    ));
return $config;
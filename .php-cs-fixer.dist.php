<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('Tests/MongoApp/var')
    ->exclude('Tests/OrmApp/var')
    ->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'phpdoc_align' => ['align' => 'left'],
    'concat_space' => ['spacing' => 'one'],
])
    ->setFinder($finder);

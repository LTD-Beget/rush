#!/usr/bin/env php
<?php

use \Symfony\Component as SC;

ini_set('display_errors', 1);
ini_set('error_reporting', -1);

require '../vendor/autoload.php';


$params = SC\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/config.yml'));

$processor = new SC\Config\Definition\Processor();
$configuration = new \LTDBeget\Rush\Configuration();

$config = $processor->processConfiguration($configuration, ['rush' => $params]);


$command = new \LTDBeget\Rush\RunCommand($config);

$command->setPrinter(new \LTDBeget\Rush\Printer());
$command->setReflector(new \LTDBeget\Rush\Reflector());

$app = new SC\Console\Application();

$app->add($command);
$app->setDefaultCommand($command->getName());

$app->run();
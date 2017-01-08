<?php

require '../vendor/autoload.php';

$readline = new \LTDBeget\Rush\Readline();
$readline->setCompleter(new \LTDBeget\Rush\TestCompleter());


while(true) {

    $line = $readline->read("app: ");
    echo $line . PHP_EOL;

}
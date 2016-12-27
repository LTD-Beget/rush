<?php

require '../vendor/autoload.php';

$readline = new \LTDBeget\Rush\Readline();

while(true) {

    $line = $readline->read("app: ");
    echo $line . PHP_EOL;

}
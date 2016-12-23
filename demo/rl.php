<?php

require '../vendor/autoload.php';

$readline = new \LTDBeget\Rush\RL();

while(true) {

    $line = $readline->read("app: ");
    echo $line . PHP_EOL;

}
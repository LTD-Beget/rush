<?php

namespace LTDBeget\Rush;


interface PrinterInterface
{

    public function printWelcome();

    public function printBye();

    /**
     * @param array $help
     * @return mixed
     */
    public function printHelp(array $help);

}
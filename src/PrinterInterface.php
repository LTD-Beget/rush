<?php

namespace LTDBeget\Rush;


use Symfony\Component\Console\Output\OutputInterface;

interface PrinterInterface
{

    /**
     * This method will be called before Rush started
     *
     * @param OutputInterface $output
     * @return void
     */
    public function init(OutputInterface $output);

    public function printWelcome();

    public function printBye();

}
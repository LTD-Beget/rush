<?php

namespace LTDBeget\Rush;


use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class Printer implements PrinterInterface
{

    const STYLE_PRIMARY = 'primary';

    /**
     * @var OutputInterface
     */
    protected $output;


    /**
     * This method will be called before Rush started
     *
     * @param OutputInterface $output
     * @return void
     */
    public function init(OutputInterface $output)
    {
        $this->output = $output;
        $this->initStyles();
    }

    public function printWelcome()
    {
        $this->printEmpty(2);
        $this->output->writeln($this->stylish("Welcome to Rush!", self::STYLE_PRIMARY));
        $this->printEmpty();
        $this->output->writeln($this->stylish("docs https://github.com/LTD-Beget/rush", self::STYLE_PRIMARY));
        $this->printEmpty(2);
    }

    public function printBye()
    {
        $this->printEmpty(2);
        $this->output->writeln($this->stylish("Bye :-)", self::STYLE_PRIMARY));
        $this->printEmpty(2);
    }

    protected function initStyles()
    {
        $formatter = $this->output->getFormatter();
        $primary = new OutputFormatterStyle('green', null, ['bold']);
        $formatter->setStyle(self::STYLE_PRIMARY, $primary);
    }

    protected function printEmpty($count = 1)
    {
        $this->output->writeln(str_repeat(PHP_EOL, $count));
    }

    protected function stylish(string $value, string $style)
    {
        return sprintf('<%2$s>%1$s</%2$s>', $value, $style);
    }

}
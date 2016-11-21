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
     * @var ColumnFormatter
     */
    protected $columnFormatter;

    /**
     * @var int
     */
    protected $helpSize;

    /**
     * Printer constructor.
     * @param OutputInterface $output
     * @param int $helpSize
     */
    public function __construct(OutputInterface $output, int $helpSize)
    {
        $this->output = $output;
        $this->helpSize = $helpSize;
        $this->columnFormatter = new ColumnFormatter();
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

    public function printHelp(array $help)
    {
        $output = $this->columnFormatter->format($help, $this->helpSize, $this->getWidthScreen());
        $this->output->writeln($output);
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

    /**
     * @return int
     */
    protected function getWidthScreen() : int
    {
        return 40;
    }

}
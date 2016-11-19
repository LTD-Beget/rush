<?php

namespace LTDBeget\Rush;


use LTDBeget\Rush\Events\Readline\BeforeReadEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RunCommand extends Command
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var PrinterInterface
     */
    protected $printer;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        parent::__construct(null);
    }

    public function setPrinter(PrinterInterface $printer)
    {
        $this->printer = $printer;
    }


    public function getPrinter() : PrinterInterface
    {
        return $this->printer;
    }

    protected function configure()
    {
        $this
            ->setName('rush')
            ->setDescription('Run interactive shell')
            ->setHelp('Console app for executing console commands with autocomplete');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->printer->init($output);

        $dispatcher = new EventDispatcher();

        $core = new Core($this->printer);

        $dispatcher->addListener(BeforeReadEvent::NAME, [$core, '']);

        $this->printer->printWelcome();

        $readline = new Readline($dispatcher, $this->config['prompt']);

        $readline->read();

        $this->printer->printBye();
    }


}
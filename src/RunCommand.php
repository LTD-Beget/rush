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
     * @var ReflectorInterface
     */
    protected $reflector;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        parent::__construct(null);
    }

    /**
     * @param PrinterInterface $printer
     */
    public function setPrinter(PrinterInterface $printer)
    {
        $this->printer = $printer;
    }

    /**
     * @return PrinterInterface
     */
    public function getPrinter() : PrinterInterface
    {
        return $this->printer;
    }

    /**
     * @param ReflectorInterface $reflector
     */
    public function setReflector(ReflectorInterface $reflector)
    {
        $this->reflector = $reflector;
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

        $core = new Core($dispatcher, $this->printer);
        $core->setShowHelp($this->config['help']['show']);


        $dispatcher->addListener(BeforeReadEvent::NAME, [$core, 'onReadlineBeforeRead']);

        $this->printer->printWelcome();

        $readline = new Readline($dispatcher, $this->config['prompt']);

        $readline->read();

        $this->printer->printBye();
    }


}
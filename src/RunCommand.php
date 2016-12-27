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
        $printer = new Printer($output, $this->config['help']['size']);

        $dispatcher = new EventDispatcher();

        $api = new API($this->reflector);
        $helpResolver = new HelpResolver($api, $this->config['help']['show'], $this->config['help']['sub']);

        $core = new Core($dispatcher, $helpResolver, $printer);

        /**
         * @uses Core::onReadlineBeforeRead()
         */
        $dispatcher->addListener(BeforeReadEvent::NAME, [$core, 'onReadlineBeforeRead']);

        $printer->printWelcome();

        $readline = new Readline_OLD($dispatcher, $this->config['prompt']);

        $readline->read();

        $printer->printBye();
    }


}
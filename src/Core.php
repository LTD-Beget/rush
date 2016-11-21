<?php

namespace LTDBeget\Rush;


use LTDBeget\Rush\Events\Core\ShowHelpEvent;
use LTDBeget\Rush\Events\Readline\BeforeReadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Core
{

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var HelpResolver
     */
    protected $helpResolver;

    /**
     * @var PrinterInterface
     */
    protected $printer;

    /**
     * @var string
     */
    protected $context = null;

    /**
     * Core constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param HelpResolver $helpResolver
     * @param PrinterInterface $printer
     */
    public function __construct(EventDispatcherInterface $dispatcher, HelpResolver $helpResolver, PrinterInterface $printer)
    {
        $this->dispatcher = $dispatcher;
        $this->helpResolver = $helpResolver;
        $this->printer = $printer;
    }

    public function onReadlineBeforeRead()
    {
        $this->resolveOutput();
    }

    protected function resolveOutput()
    {
        $help = $this->helpResolver->resolve($this->context);
        $this->printer->printHelp($help);
    }

}
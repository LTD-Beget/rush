<?php

namespace LTDBeget\Rush;


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
     * @var Printer
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
     * @param Printer $printer
     */
    public function __construct(EventDispatcherInterface $dispatcher, HelpResolver $helpResolver, Printer $printer)
    {
        $this->dispatcher = $dispatcher;
        $this->helpResolver = $helpResolver;
        $this->printer = $printer;
    }

    /**
     * @param BeforeReadEvent $event
     */
    public function onReadlineBeforeRead(BeforeReadEvent $event)
    {
        $this->resolveOutput();
    }

    /**
     * TODO: cache DISABLE?
     */
    protected function resolveOutput()
    {
        $help = $this->helpResolver->resolve($this->context);

        if ($help !== HelpResolver::DISABLE) {
            $this->printer->printHelp($help);
        }
    }

}
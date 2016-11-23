<?php

namespace LTDBeget\Rush;


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
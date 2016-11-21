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
     * @var PrinterInterface
     */
    protected $printer;

    /**
     * @var string
     */
    protected $_showHelp;

    /**
     * @var bool
     */
    protected $helpShown = false;

    /**
     * @var string
     */
    protected $context;


    /**
     * Core constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param PrinterInterface $printer
     */
    public function __construct(EventDispatcherInterface $dispatcher, PrinterInterface $printer)
    {
        $this->dispatcher = $dispatcher;
        $this->printer = $printer;
    }

    public function onReadlineBeforeRead(BeforeReadEvent $event)
    {
        $this->resolveOutput();
    }

    /**
     * @param string $showHelp
     */
    public function setShowHelp(string $showHelp)
    {
        $this->_showHelp = $showHelp;
    }

    protected function resolveOutput()
    {
        if($this->_showHelp === ConfigInterface::SHOW_HELP_NEVER) {
            return;
        }

        if($this->_showHelp === ConfigInterface::SHOW_HELP_ONCE) {
            if($this->helpShown) {
               return;
            }

            $this->helpShown = true;
        }

        $event = new ShowHelpEvent([]);
        $this->dispatcher->dispatch(ShowHelpEvent::NAME, $event);
    }

}
<?php

namespace LTDBeget\Rush\Events\Core;


use Symfony\Component\EventDispatcher\Event;

class ShowHelpEvent extends Event
{

    const NAME = 'core.show.help';

    /**
     * @var array
     */
    protected $help;

    /**
     * ShowHelpEvent constructor.
     * @param array $help
     */
    public function __construct(array $help)
    {
        $this->help = $help;
    }

    /**
     * @return array
     */
    public function getHelp(): array
    {
        return $this->help;
    }

}
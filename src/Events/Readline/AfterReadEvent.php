<?php

namespace LTDBeget\Rush\Events\Readline;


use Symfony\Component\EventDispatcher\Event;

class AfterReadEvent extends Event
{

    const NAME = 'readline.after.read';

    /**
     * @var string
     */
    protected $input;

    /**
     * AfterReadEvent constructor.
     * @param string $input
     */
    public function __construct(string $input)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

}
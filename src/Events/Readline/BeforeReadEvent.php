<?php

namespace LTDBeget\Rush\Events\Readline;


use Symfony\Component\EventDispatcher\Event;

class BeforeReadEvent extends Event
{

    const NAME = 'readline.before.read';

}
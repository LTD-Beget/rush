<?php

namespace LTDBeget\Rush;


use LTDBeget\Rush\Events\Readline\AfterReadEvent;
use LTDBeget\Rush\Events\Readline\BeforeReadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Readline
{

    /**
     * @var CompleteInterface
     */
    protected $completer;

    /**
     * @var string
     */
    protected $prompt;

    /**
     * @var string
     */
    protected $defaultPrompt;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Readline constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param string $prompt
     */
    public function __construct(EventDispatcherInterface $dispatcher, string $prompt)
    {
        $this->dispatcher = $dispatcher;
        $this->prompt = $this->defaultPrompt = $prompt;

        $this->init();
    }

    /**
     * @uses Readline::onCompletion()
     */
    protected function init()
    {
        $this->completionFunction([$this, 'onCompletion']);
    }

    public function read()
    {
        do {
            $this->dispatcher->dispatch(BeforeReadEvent::NAME, new BeforeReadEvent());

            $line = trim($this->readlineRead($this->loadPrompt()));
            $this->addHistory($line);

            $this->dispatcher->dispatch(AfterReadEvent::NAME, new AfterReadEvent($line));
        } while ($line !== false && $line !== 'quit');
    }

    /**
     * @param CompleteInterface $completer
     */
    public function registerCompleter(CompleteInterface $completer)
    {
        $this->completer = $completer;
    }

    /**
     * @param string $current
     * @return array
     */
    protected function onCompletion(string $current): array
    {
        $prev = $this->getPrev($current);

        return $this->completer->complete($prev, $current);
    }

    /**
     * @return string
     */
    protected function loadPrompt(): string
    {
        return exec(sprintf('echo "%s"', "rush> "));
    }

    /**
     * @param string $input
     * @return string
     */
    protected function getPrev(string $input): string
    {
        $line = $this->getLine();

        if ($input !== '') {
            $line = substr($line, 0, -(strlen($input) + 1));
        }

        return trim($line);
    }

    /**
     * @return string
     */
    protected function getLine(): string
    {
        $info = $this->info();

        return substr($info['line_buffer'], 0, $info['end']);
    }

    /**
     * @param string|null $prompt
     * @return string
     */
    protected function readlineRead(string $prompt = null): string
    {
        return readline($prompt);
    }

    /**
     * @param $callable
     */
    protected function completionFunction($callable)
    {
        readline_completion_function($callable);
    }

    /**
     * @param string $line
     */
    protected function addHistory(string $line)
    {
        readline_add_history($line);
    }

    /**
     * @return array
     */
    protected function info(): array
    {
        return readline_info();
    }

}
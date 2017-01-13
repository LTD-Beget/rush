<?php

namespace LTDBeget\Rush;


use Hoa\Console\Parser;

class InputInfo implements InputInfoInterface
{

    protected $args;
    protected $options;
    protected $pos;
    protected $current;

    public function __construct(string $input)
    {
        $hasOpenedQuotes = $this->hasOpenedQuotes($input);

        if ($hasOpenedQuotes) {
            $input .= '"';
        }

        $parser = new Parser();
        $parser->parse($input);
        $this->args = $parser->getInputs();
        $this->options = $parser->getSwitches();

        $count = count($this->args) + count($this->options);

        if ($count) {
            $this->pos = $count - 1;

            if ($this->hasNewEmptyInput($input)) {
                $this->pos++;
            }

        } else {
            $this->pos = 0;
        }

        if (empty($this->options)) {
            $c = count($this->args);

            if ($c) {
                $this->current = $this->args[$c - 1];
            }

        } else {
            $value = end($this->options);
            $key = key($this->options);
            $this->current = [$key => $value];
        }

    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getPos(): int
    {
        return $this->pos;
    }

    public function getCurrent(): string
    {
        return $this->current;
    }

    /**
     * @param string $input
     * @return bool
     */
    protected function hasOpenedQuotes(string $input): bool
    {
        return (substr_count($input, "\"") % 2) !== 0;
    }

    /**
     * @param string $input
     * @return bool
     */
    protected function hasNewEmptyInput(string $input): bool
    {
        return substr($input, -1) === ' ';
    }

}
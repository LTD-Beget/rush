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

        $nArgs = count($this->args);
        $count = $nArgs + count($this->options);
        $this->pos = $count ? $count - 1 : 0;

        if(!empty($this->options)) {
            $value = end($this->options);
            $key = key($this->options);
            $this->current = [$key => $value];

            return;
        }

        if(!$nArgs) {
            $this->current = '';

            return;
        }

        if ($this->hasNewEmptyInput($input)) {
            $this->pos++;
            $this->current = '';
        } else {
            $this->current = $this->args[$this->pos];
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
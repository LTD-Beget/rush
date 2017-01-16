<?php

namespace LTDBeget\Rush;

class InputBuffer
{

    const EMPTY = '';

    /**
     * @var int
     */
    protected $pos = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var string
     */
    protected $input = self::EMPTY;

    /**
     * @var string
     */
    protected $prompt = '';
    
    /**
     * @param string $prompt
     */
    public function setPrompt(string $prompt)
    {
        $this->prompt = $prompt;
        $this->offset = strlen($prompt);
    }

    public function reset()
    {
        $this->input = self::EMPTY;
        $this->pos = 0;
    }

    /**
     * @return bool
     */
    public function isEndOfInput(): bool
    {
        return $this->pos === strlen($this->getInput());
    }

    /**
     * @return InputInfo
     */
    public function getInputInfo(): InputInfo
    {
        return new InputInfo($this->getCurrent());
    }

    /**
     * @return string
     */
    public function getPrompt(): string
    {
        return $this->prompt;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getCurrent(): string
    {
        return substr($this->input, 0, $this->pos);
    }

    public function insert(string $value)
    {
        $l = strlen($value);
        $this->input = implode("", [substr($this->input, 0, $this->pos), $value, substr($this->input, $this->pos)]);
        $this->next($l);
    }

    public function prev()
    {
        if ($this->pos > 0) {
            $this->pos--;
        }
    }

    public function next(int $step = 1)
    {
        if (($this->pos + ($step - 1)) < strlen($this->input)) {
            $this->pos = $this->pos + $step;
        }
    }

    /**
     * @return int Absolute x pos (including prompt)
     */
    public function getAbsolutePos(): int
    {
        return $this->offset + $this->pos;
    }

    public function removeChar()
    {
        if (!$this->isEmpty()) {
            $this->input =  substr($this->input, 0, $this->pos - 1) . substr($this->input, $this->pos);
            $this->pos--;

            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        return $this->input === self::EMPTY;
    }

}
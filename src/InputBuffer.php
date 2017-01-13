<?php

namespace LTDBeget\Rush;

class InputBuffer
{

    /**
     * @var string
     */
    protected $buffer;

    /**
     * @var int
     */
    protected $pos = 0;

    const EMPTY = '';

    public function clear()
    {
        $this->buffer = self::EMPTY;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return substr($this->buffer, 0, $this->pos);
    }

    public function insert(string $value)
    {
        $l = strlen($value);
        $this->buffer = implode("", [substr($this->buffer, 0, $this->pos), $value, substr($this->buffer, $this->pos)]);
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
        if (($this->pos + ($step - 1)) < strlen($this->buffer)) {
            $this->pos = $this->pos + $step;
        }
    }

    public function getPos()
    {
        return $this->pos;
    }

    public function removeChar()
    {
        if (!$this->isEmpty()) {
            $this->buffer = substr($this->buffer, 0, -1);
            $this->pos--;

            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        return $this->buffer === self::EMPTY;
    }

}
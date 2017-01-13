<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Output;

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
    protected $buffer = self::EMPTY;

    /**
     * @var string
     */
    protected $prompt = '';

    public function flush(Output $output)
    {
        Cursor::clear('line');
        $output->writeString($this->prompt . $this->buffer);
        Cursor::move("LEFT");
        Cursor::move('right', $this->getAbsolutePos());
    }
    
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
        $this->buffer = self::EMPTY;
        $this->pos = 0;
    }

    /**
     * @return string
     */
    public function getCurrent(): string
    {
        return substr($this->buffer, 0, $this->pos);
    }

    public function getInfo()
    {
        return [
            'buffer' => $this->prompt . $this->buffer,
            'pos' => $this->getAbsolutePos()
        ];
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
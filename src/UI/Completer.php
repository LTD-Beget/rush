<?php

namespace LTDBeget\Rush\UI;


use LTDBeget\Rush\Utils\Console;

class Completer
{

    protected $dict;
    protected $height;
    protected $pos = self::POS_START;
    protected $offset = 0;
    protected $size;
    protected $reverse = false;

    const POS_START = 1;

    /**
     * Completer constructor.
     * @param array $dict
     * @param int $height
     */
    function __construct(array $dict, int $height)
    {
        $this->dict = $dict;
        $this->height = $height;
        $this->size = count($dict);
    }

    public function prev()
    {
        if($this->pos === self::POS_START) {
            $this->pos = $this->size;
            $this->reverse = true;
        } else {
            $this->pos--;
        }
//        $this->pos = ($this->pos === self::POS_START) ? $this->size : $this->pos - 1;
    }

    public function next()
    {
        if($this->pos === $this->size) {
            $this->pos = self::POS_START;
            $this->reverse = false;
        } else {
            $this->pos++;
        }
//        $this->pos = ($this->pos === $this->size) ? self::POS_START : $this->pos + 1;
    }

    /**
     * @return string
     */
    public function getOutput() : string
    {
        if(!$this->reverse) {
            $this->offset = 0;
        } else {
            $this->offset = $this->size - $this->height + 1;
        }

        if($this->pos > $this->height) {
            $this->offset = $this->pos - $this->height;
        }

        $dict = array_slice($this->dict, $this->offset, $this->height);
        $width = $this->getWidth($dict);
        $output = '';

        $activeMarked = false;
        $scrollMarked = false;
        foreach ($dict as $k => $word) {

            $fgcolor = 37;
            if(!$activeMarked && ($this->offset + $k + 1) === $this->pos) {
                $fgcolor = 30;
                $activeMarked = true;
            }

            $output .= Console::ansiFormat(' ' . $this->normalize($word, $width) . ' ', [$fgcolor, 46, 1]);

            if(!$scrollMarked) {
                $scroll = Console::ansiFormat(' ', [48, 2, 50, 50, 50]);
                $scrollMarked = true;
            } else {
                $scroll = Console::ansiFormat(' ', [48, 2, 90, 90, 90]);
            }

            $output .= $scroll . PHP_EOL;
        }

        return $output;
    }

    /**
     * @param array $dict
     * @return int
     */
    protected function getWidth(array $dict) : int
    {
        $max = 0;

        foreach ($dict as $word) {
            $l = strlen($word);

            if($l > $max) {
                $max = $l;
            }
        }

        return $max;
    }

    /**
     * @param string $str
     * @param int $length
     * @return string
     */
    protected function normalize(string $str, int $length) : string
    {
        $void = str_repeat(' ', $length - mb_strlen($str));

        return $str . $void;
    }

}
<?php

namespace LTDBeget\Rush\UI;


use Hoa\Console\Cursor;
use Hoa\Console\Output;
use LTDBeget\Rush\Utils\Console;
use LTDBeget\Rush\Utils\Str;

class Window
{

    protected $dict;
    protected $height;
    protected $posMax;
    protected $ratePerLine;
    protected $pos = self::POS_START;
    protected $offset = 0;
    protected $reverse = false;

    /**
     * @var Output
     */
    protected $output;

    const POS_START = 0;

    /**
     * Completer constructor.
     * @param array $dict
     * @param int $height
     */
    function __construct(Output $output, array $dict, int $height)
    {
        $this->output = $output;
        $this->dict = $dict;
        $this->height = $height;
        $count = count($dict);
        $this->posMax = $count - 1;
        $this->ratePerItem = intdiv(100, $count);
        $this->ratePerLine = intdiv(100, $this->height);
    }

    public function prev()
    {
        if($this->pos === self::POS_START) {
            $this->pos = $this->posMax;
            $this->reverse = true;
        } else {
            $this->pos--;
        }
    }

    public function next()
    {
        if($this->pos === $this->posMax) {
            $this->pos = self::POS_START;
            $this->reverse = false;
        } else {
            $this->pos++;
        }
    }

    public function render(int $x)
    {
        Cursor::save();
        Cursor::move('down');
        Cursor::move('LEFT');

        Cursor::move('right', $x);

        $output = $this->getOutput($x);
        $this->output->writeString($output);
        Cursor::restore();
    }

    public function remove()
    {
        Cursor::clear("down");
    }

    /**
     * @return string
     */
    public function getOutput(int $x) : string
    {
        $this->defineOffset();
        $dict = $this->getSlice();

        $width = Str::getMaxLength($dict);
        $output = '';

        $activeFound = false;
        $scrollDrawn = false;
        $posActive = $this->pos - $this->offset;
        $posScroll = $this->getScrollPos();

        foreach ($dict as $k => $word) {

            $fgcolor = 37;
            if(!$activeFound && $k === $posActive) {
                $fgcolor = 30;
                $activeFound = true;
            }

            $output .= Console::ansiFormat(' ' . Str::normalize($word, $width) . ' ', [$fgcolor, 46, 1]);

            if(!$scrollDrawn && $k === $posScroll) {
                $scroll = Console::ansiFormat(' ', [48, 2, 50, 50, 50]);
                $scrollDrawn = true;
            } else {
                $scroll = Console::ansiFormat(' ', [48, 2, 90, 90, 90]);
            }

            $output .= $scroll . PHP_EOL . "\033[{$x}C";
        }

        return $output;
    }

    protected function defineOffset()
    {
        if($this->reverse) {

            if($this->pos > ($this->posMax - $this->height)) {
                $this->offset = ($this->posMax + 1) - $this->height;
            } else {
                $this->offset = $this->pos;
            }

        } else {

            if($this->pos < $this->height) {
                $this->offset = 0;
            } else {
                $this->offset = ($this->pos + 1) - $this->height;
            }

        }
    }

    /**
     * @return array
     */
    protected function getSlice() : array
    {
        return array_slice($this->dict, $this->offset, $this->height);
    }

    /**
     * @return int
     */
    protected function getScrollPos() : int
    {
        $progress = $this->pos * $this->ratePerItem;
        $pos = 0;

        while (true) {
            if($progress < (($pos + 1) * $this->ratePerLine)) {
                break;
            }

            $pos++;
        }

        return $pos;
    }

}
<?php

namespace LTDBeget\Rush\UI;


use Hoa\Console\Cursor;
use Hoa\Console\Output;
use LTDBeget\Rush\Utils\Console;
use LTDBeget\Rush\Utils\Str;

/**
 * Class Window
 * @package LTDBeget\Rush\UI
 * TODO проверить использование pos в контексте того что он может быть нул
 * TODO синхронизировать x после комплита
 *
 */
class Window
{

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var int
     */
    protected $pos;

    /**
     * @var int
     */
    protected $posMax;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var bool
     */
    protected $reverse;

    /**
     * @var int
     */
    protected $ratePerLine;

    /**
     * @var int
     */
    protected $ratePerItem;



    const POS_START = 0;

    /**
     * Completer constructor.
     * @param Output $output
     * @param int $height
     */
    function __construct(Output $output, int $height)
    {
        $this->output = $output;
        $this->height = $height;

        $this->resetScrolling();
    }

    public function scrollUp()
    {
        if (!$this->isActive() || $this->pos === self::POS_START) {
            $this->pos = $this->posMax;
            $this->offset = $this->posMax - $this->height + 1;
            $this->reverse = true;
        } else {
            $this->pos--;

            if($this->pos < $this->offset) {
                $this->offset--;
            }
        }

    }

    public function scrollDown()
    {
        if (!$this->isActive()) {
            $this->pos = self::POS_START;
        } else if ($this->pos === $this->posMax) {
            $this->pos = self::POS_START;
            $this->reverse = false;
            $this->offset = 0;
        } else {
            $this->pos++;

            if($this->pos > ($this->offset + $this->height - 1)) {
                $this->offset++;
            }
        }
    }

    public function loadContent(array $content)
    {
        $this->content = $content;

        if(empty($this->content)) {
            return;
        }

        $count = count($content);
        $this->posMax = $count - 1;
        $this->ratePerItem = intdiv(100, $count);
        $this->ratePerLine = intdiv(100, $this->height);
    }

    public function isActive()
    {
        return $this->pos !== null;
    }

    public function getValue()
    {
        return $this->content[$this->getPosActive()];
    }

    public function show(int $x)
    {
        if(empty($this->content)) {
            return;
        }

        Cursor::save();

        Cursor::move('down');
        Cursor::move('LEFT');
        Cursor::move('right', $x);

        $output = $this->getOutput($x);
        $this->output->writeString($output);

        Cursor::restore();
    }

    public function hide()
    {
        Cursor::clear("down");
    }

    public function resetScrolling()
    {
        $this->pos = null;
        $this->offset = 0;
        $this->reverse = false;
    }

    /**
     * @return string
     */
    protected function getOutput(int $x): string
    {
        $dict = $this->getSlice();

        $width = Str::getMaxLength($dict);
        $output = '';

        $activeFound = !($this->isActive());
        $scrollDrawn = false;
        $posActive = $this->getPosActive();
        $posScroll = $this->getPosScroll();

        foreach ($dict as $k => $word) {

            $fgcolor = 37;

            if (!$activeFound && $k === $posActive) {
                $fgcolor = 30;
                $activeFound = true;
            }

            $output .= Console::ansiFormat(' ' . Str::normalize($word, $width) . ' ', [$fgcolor, 46, 1]);


            if (!$scrollDrawn && $k === $posScroll) {
                $scroll = Console::ansiFormat(' ', [48, 2, 50, 50, 50]);
                $scrollDrawn = true;
            } else {
                $scroll = Console::ansiFormat(' ', [48, 2, 90, 90, 90]);
            }

            $output .= $scroll . PHP_EOL . "\033[{$x}C";
        }

        return $output;
    }

    /**
     * @return int
     */
    protected function getPosActive(): int
    {
        return $this->pos - $this->offset;
    }

    /**
     * @return array
     */
    protected function getSlice(): array
    {
        return array_slice($this->content, $this->offset, $this->height);
    }

    /**
     * @return int
     */
    protected function getPosScroll(): int
    {
        $progress = $this->pos * $this->ratePerItem;
        $pos = 0;

        while (true) {
            if ($progress < (($pos + 1) * $this->ratePerLine)) {
                break;
            }

            $pos++;
        }

        return $pos;
    }

}
<?php

namespace LTDBeget\Rush;


class Window
{

    protected $x;
    protected $y;
    protected $color;
    protected $asciiColor = '\x1b[%dm';


    public function __construct($x, $y, $color)
    {
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }

    public function render()
    {
        $body = "";
        $rows = $this->y;

        while ($rows--) {
            $line = str_repeat("*", $this->x - 1) . PHP_EOL;
            $body .= $line;
        }

        $body = substr($body, 0, -1);

        fwrite(STDOUT, "\n");
        fwrite(STDOUT, $this->colorize($body, 32));
    }

    protected function colorize($text, $color)
    {
        return sprintf($this->asciiColor, $color) . $text . sprintf($this->asciiColor, 0);
    }


}
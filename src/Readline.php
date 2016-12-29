<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Input;
use Hoa\Console\Output;
use LTDBeget\Rush\UI\Window;
use LTDBeget\Rush\Utils\Console;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Readline
{

    protected $buffer;
    protected $input;
    protected $completer;
    protected $x;
    protected $window;

    public function __construct()
    {
        $this->input = new Input();
        $this->output = new Output();
        $dict = [
            'adverse',
            'adventure',
            'aura',
            'application',
            'apple',
            'cache',
            'fixture',
            'message',
            'migrate',
            'serve',
            'user',
            'network',
            'backup',
            'customer'
        ];
        $this->window = new Window($this->output, $dict, 4);
    }

    public function read(string $prompt): string
    {
        $isNewInput = true;
        $this->clearBuffer();


        while (true) {

            if ($isNewInput) {
                echo $prompt;
                $isNewInput = false;
                $this->x = $this->getX();
            }

            $char = $this->input->readCharacter();
            $this->window->remove();
            $code = ord($char);

            switch ($code) {
                case Console::CODE_TAB:

                    //
                    break;
                case Console::CODE_ENTER:
                    $isNewInput = true;
                    $this->output->writeString(PHP_EOL);
                    $this->clearBuffer();
                    break;
                case Console::CODE_DELETE:
                    $this->buffer = substr($this->buffer, 0, -1);
                    Cursor::move("left");
                    Cursor::clear("right");
                    $this->x--;

                    $this->window->render($this->x);
                    break;
                default:
                    $this->buffer .= $char;
                    $this->output->writeString($char);
                    $this->x++;

                    $this->window->render($this->x);
            }

        }
        return $this->buffer;
    }

    protected function clearBuffer()
    {
        $this->buffer = '';
    }

    protected function getX()
    {
        return Cursor::getPosition()['x'];
    }

}
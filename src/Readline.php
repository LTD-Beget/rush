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

    protected $keymap;
    protected $isNewInput;

    protected $dict = [
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

    public function __construct()
    {
        $this->input = new Input();
        $this->output = new Output();
        $this->window = new Window($this->output, $this->dict, 4);
        $this->bindKeyByCode(127, 'bindDEL');
        $this->bindKeyByCode(10, 'bindLF');
    }


    /**
     * @param int $key ASCII code
     * @param string $handler Function name
     */
    public function bindKeyByCode(int $key, string $handler)
    {
        $this->keymap[$key] = [$this, $handler];
    }

    public function read(string $prompt): string
    {
        $this->isNewInput = true;
        $this->clearBuffer();

        while (true) {

            if ($this->isNewInput) {
                $this->output->writeString($prompt);
                $this->isNewInput = false;
                $this->x = $this->getX();
            }

            $char = $this->input->read(3);
            $this->window->remove();
            $code = ord($char);

            if (isset($this->keymap[$code])) {
                call_user_func($this->keymap[$code], $this);
                continue;
            } else {
                $this->buffer .= $char;
                $this->output->writeString($char);
                $this->x++;
                $this->window->prev();
                $this->window->render($this->x);
            }

        }
        return $this->buffer;
    }

    protected function bindDEL(Readline $self)
    {
        $self->buffer = substr($self->buffer, 0, -1);
        Cursor::move("left");
        Cursor::clear("right");
        $self->x--;
    }

    protected function bindLF(Readline $self)
    {
        $self->isNewInput = true;
        $self->output->writeString(PHP_EOL);
        $self->clearBuffer();
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
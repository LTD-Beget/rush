<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Input;
use Hoa\Console\Output;
use LTDBeget\Rush\UI\Completer;
use LTDBeget\Rush\Utils\Console;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Readline
{

    protected $buffer;
    protected $input;
    protected $completer;

    public function __construct()
    {
        $this->defineImmediatelyReading();
        $this->input = new Input();
        $this->output = new Output();

    }

    public function read(string $prompt): string
    {
        $isNewInput = true;
        $this->buffer = '';

        while (true) {

            if ($isNewInput) {
                echo $prompt;
                $isNewInput = false;
            }

            $char = $this->input->readCharacter();

            $code = ord($char);

            switch ($code) {
                case Console::CODE_TAB:
                    $complete = [
                        'asset',
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
                    $completer = new Completer($complete, 4);
                    $completer->prev();
                    $completer->prev();
//                    $completer->next();
//                    $completer->next();
//                    $completer->next();
//                    $completer->next();
                    $this->output->writeString(PHP_EOL);
                    $this->output->writeString($completer->getOutput());
                    break;
                case Console::CODE_ENTER:
                    $isNewInput = true;
                    $this->output->writeString(PHP_EOL);
                    break;
                default:
                    $this->buffer .= $char;
                    $this->output->writeString($char);
            }

        }
        return $this->buffer;
    }

    protected function defineImmediatelyReading()
    {
        readline_callback_handler_install('', function () {
        });
    }

}
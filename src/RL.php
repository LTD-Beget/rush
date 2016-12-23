<?php

namespace LTDBeget\Rush;


use SebastianBergmann\CodeCoverage\Report\PHP;

class RL
{

    protected $buffer;
    protected $formatter;


    public function __construct()
    {
        $this->defineImmediatelyReading();
        $this->formatter = new ColumnFormatter();
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
            $r = array(STDIN);
            $w = NULL;
            $e = NULL;
            $n = stream_select($r, $w, $e, null);
            if ($n && in_array(STDIN, $r)) {
                $char = stream_get_contents(STDIN, 1);
                $code = ord($char);

                switch ($code) {
                    case ASCIIInterface::CODE_TAB:
                        $window= new Window(10,5, 'gray');
                        $window->render();
//                        fwrite(STDOUT, "complete init" . PHP_EOL);
                        break;
                    case ASCIIInterface::CODE_CR:
                        $isNewInput = true;
                        fwrite(STDOUT, PHP_EOL);
                        break;
                    default:
                        $this->buffer .= $char;
                        fwrite(STDOUT, $char);
                }

            }
        }
        return $this->buffer;
    }

    protected function defineImmediatelyReading()
    {
        readline_callback_handler_install('', function () {});
    }

}
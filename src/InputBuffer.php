<?php

namespace LTDBeget\Rush;


use Hoa\Console\Parser;

class InputBuffer
{

    protected $buffer;


    const EMPTY = '';

    public function clear()
    {
        $this->set(self::EMPTY);
    }

    public function getValue()
    {
        return $this->buffer;
    }

    public function set(string $value)
    {
        $this->buffer = $value;
    }

    public function add(string $value)
    {
        $this->buffer .= $value;
    }

    public function removeChar()
    {
        if (!$this->isEmpty()) {
            $this->buffer = substr($this->buffer, 0, -1);

            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        return $this->buffer === self::EMPTY;
    }

//    public function getInfo()
//    {
//        $parser = new Parser();
//        $parser->parse($this->buffer);
//
//        $args = $parser->getInputs();
//        $options = $parser->getSwitches();
//
////        $info = new InputInfo();
////
////        $info->setArgs($args);
////        $info->setOptions($options);
//
////        $current = (empty($args)) ? end
////        if(empty($args)) {
////
////        }
//
//        $current = $this->getInputCurrent();
//        $prev = $this->getInputPrev($current);
//        $count = $this->countTokens($prev);
//
//        if($prev === self::EMPTY) {
//            $count++;
//        }
//
//        return [
//            'prev' => $prev,
//            'current' => $current,
//            'pos' => $count
//        ];
//    }
//
//    public function getInputCurrent()
//    {
//        $parser = new Parser();
//        $parser->parse($this->buffer);
//        preg_match('/\s*([\w]+)$/', $this->buffer, $matches);
//
//        return $matches[1] ?? self::EMPTY;
//    }
//
//    protected function getInputPrev($exclude = null): string
//    {
//        if ($exclude === null) {
//            $exclude = $this->getInputCurrent();
//        }
//
//        return trim(substr($this->getValue(), 0, -strlen($exclude)));
//    }
//
//    protected function countTokens(string $input) : int
//    {
//        $parser = new Parser();
//        $parser->parse($input);
//
//        return count($parser->getInputs()) + count($parser->getSwitches());
//    }

}
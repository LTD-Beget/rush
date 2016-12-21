<?php

namespace LTDBeget\Rush;


interface ParserInterface
{

    /**
     * @param string $input
     * @return array
     */
    public function parse(string $input) : array;

}
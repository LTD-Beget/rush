<?php

namespace LTDBeget\Rush;


interface CompleteInterface
{

    /**
     * @param string $prev
     * @param string $current
     * @return array
     */
    public function complete(string $prev, string $current) : array;

}
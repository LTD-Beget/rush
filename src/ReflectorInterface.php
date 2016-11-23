<?php

namespace LTDBeget\Rush;


interface ReflectorInterface
{

    /**
     * @return array
     */
    public function commands() : array;

    /**
     * @param string $command
     * @return array
     */
    public function options(string $command) : array;

    /**
     * If none, return ''
     * @return string
     */
    public function getSeparator() : string;

}
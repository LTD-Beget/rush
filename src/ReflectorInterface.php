<?php

namespace LTDBeget\Rush;


interface ReflectorInterface
{

    const NONE_SEPARATOR = '';

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
     * If none, return self::NONE_SEPARATOR
     * @return string
     */
    public function getSeparator() : string;

}
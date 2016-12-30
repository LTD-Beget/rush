<?php

namespace LTDBeget\Rush\Utils;


class Console
{

    const CODE_SP = 32;
    const CODE_TAB = 9;
    const CODE_LF = 10;
    const CODE_ENTER = 13;
    const CODE_ESC = 27;
    const CODE_DELETE = 127;

    const SGR_BOLD = 1;
    const SGR_ADDITIONAL_BG = 48;

    /**
     * @param string $string
     * @param array $format
     * @return string
     */
    public static function ansiFormat(string $string, array $format = []) : string
    {
        $code = implode(';', $format);

        return "\033[0m" . ($code !== '' ? "\033[" . $code . 'm' : '') . $string . "\033[0m";
    }

}
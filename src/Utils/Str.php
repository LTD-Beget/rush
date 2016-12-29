<?php

namespace LTDBeget\Rush\Utils;


class Str
{

    /**
     * @param array $list
     * @return int
     */
    public static function getMaxLength(array $list) : int
    {
        $max = 0;

        foreach ($list as $str) {
            $l = mb_strlen($str);

            if($l > $max) {
                $max = $l;
            }
        }

        return $max;
    }

    /**
     * @param string $str
     * @param int $length
     * @return string
     */
    public static function normalize(string $str, int $length) : string
    {
        return $str . str_repeat(' ', $length - mb_strlen($str));
    }

}
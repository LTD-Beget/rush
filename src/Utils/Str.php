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

}
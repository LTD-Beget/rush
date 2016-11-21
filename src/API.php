<?php

namespace LTDBeget\Rush;


class API
{

    /**
     * Если есть такая комманда, вернет опшены, если есть
     * такая super группа вернет все sub
     * @param $value
     * @return array
     */
    public function find($value) : array
    {
        return [];
    }

    /**
     * Если sub то выводим все, если нет то фул комманды и super группы
     *
     * @param bool $sub
     * @return array
     */
    public function commands(bool $sub = true) : array
    {
        return [];
    }

}
<?php

namespace LTDBeget\Rush;


interface CompleteInterface
{

    /**
     * @param InputInfoInterface $info
     * @return array
     */
    public function complete(InputInfoInterface $info) : array;

}
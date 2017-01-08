<?php

namespace LTDBeget\Rush;


interface InputInfoInterface
{

    public function getArgs(): array;

    public function getOptions(): array;

//    public function getCurrent(): string;

    public function getPos(): int;

}
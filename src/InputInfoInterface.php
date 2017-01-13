<?php

namespace LTDBeget\Rush;


interface InputInfoInterface
{

    public function getArgs(): array;

    public function getOptions(): array;

    public function getPos(): int;

    public function getCurrent(): string;
}
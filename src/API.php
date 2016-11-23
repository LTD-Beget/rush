<?php

namespace LTDBeget\Rush;


class API
{

    /**
     * @var ReflectorInterface
     */
    protected $reflector;

    /**
     * API constructor.
     * @param ReflectorInterface $reflector
     */
    public function __construct(ReflectorInterface $reflector)
    {
        $this->reflector = $reflector;
    }

    /**
     * Если есть такая комманда, вернет опшены, если есть
     * такая super группа вернет все sub
     * @param $value
     * @return array
     */
    public function find($value) : array
    {
        $commands = $this->reflector->commands();

        $key = array_search($value, $commands);

        if ($key !== false) {
            return $this->reflector->options($commands);
        }

        $sub = [];
        $separator = $this->reflector->getSeparator();

        foreach ($commands as $command) {
            $pos = strpos($command, $value . $separator);

            if ($pos === false) {
                continue;
            }

            $sub[] = substr($commands, strlen($value . $separator));
        }

        return $sub;
    }

    /**
     * Если sub то выводим все, если нет то фул комманды и super группы
     *
     * @param bool $sub
     * @return array
     */
    public function commands(bool $sub = true) : array
    {
        if ($sub) {
            return $this->reflector->commands();
        }

        $result = [];

        $separator = $this->reflector->getSeparator();

        foreach ($this->reflector->commands() as $command) {
            $pos = strpos($command, $separator);
            $result[] = ($pos === false) ? $command : substr($command, 0, $pos);
        }

        return array_unique($result, SORT_STRING);
    }

}
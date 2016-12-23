<?php

namespace LTDBeget\Rush;


class Reflector implements ReflectorInterface
{

    protected $api;

    public function __construct()
    {
        $this->api = [
            'orm:generate' => [
                'name', 'size'
            ],
            'orm:dump' => [
                'force'
            ],
            'network/ping' => [
                'min', 'max'
            ],
            'network' => [
                'ip'
            ]
        ];
    }


    /**
     * @return array
     */
    public function commands() : array
    {
        return array_keys($this->api);
    }

    /**
     * @param string $command
     * @return array
     */
    public function options(string $command) : array
    {
        return $this->api[$command];
    }

    /**
     * If none, return ''
     * @return string
     */
    public function getSeparator(): string
    {
        return ':';
    }
}
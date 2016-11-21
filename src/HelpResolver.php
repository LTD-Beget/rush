<?php

namespace LTDBeget\Rush;


class HelpResolver
{

    /**
     * @var API
     */
    protected $api;

    /**
     * @var string
     */
    protected $show;

    /**
     * @var bool
     */
    protected $shown = false;

    /**
     * @var bool
     */
    protected $sub;

    /**
     * HelpResolver constructor.
     * @param API $api
     * @param string $show
     * @param bool $sub
     */
    public function __construct(API $api, string $show, bool $sub)
    {
        $this->api = $api;
        $this->show = $show;
        $this->sub = $sub;
    }

    /**
     * @param string $context
     * @return array
     */
    public function resolve(string $context) : array
    {
        $help = [];

        if ($this->show === ConfigInterface::SHOW_HELP_NEVER) {
            return $help;
        }

        if ($this->show === ConfigInterface::SHOW_HELP_ONCE) {
            if ($this->shown) {
                return $help;
            }

            $this->shown = true;
        }

        if ($context === null) {
            return $this->api->commands($this->sub);
        }

        return $this->api->find($context);
    }

}
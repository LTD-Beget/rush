<?php

namespace LTDBeget\Rush;


class HelpResolver
{

    const DISABLE = false;

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
     * @var array
     */
    protected $help = [];

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
     * TODO: make caching results?
     * @param string $context
     * @return array|FALSE
     */
    public function resolve(string $context = null)
    {
        if ($this->show === ConfigInterface::SHOW_HELP_NEVER) {
            return self::DISABLE;
        }

        if ($this->show === ConfigInterface::SHOW_HELP_ONCE) {
            if ($this->shown) {
                return self::DISABLE;
            }

            $this->shown = true;
        }

        if ($context === null) {
            return $this->api->commands($this->sub);
        }

        return $this->api->find($context);
    }

}
<?php

namespace LTDBeget\Rush;


class Context
{

    const EMPTY = '';

    /**
     * @var string
     */
    protected $context;

    /**
     * Context constructor.
     * @param string $context
     */
    public function __construct(string $context = self::EMPTY)
    {
        $this->context = $context;
    }
    
    /**
     * @param string $context
     */
    public function setContext(string $context)
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getContext() : string
    {
        return $this->context;
    }

    public function clear()
    {
        $this->context = self::EMPTY;
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return $this->context === self::EMPTY;
    }
    
}
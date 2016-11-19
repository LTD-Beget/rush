<?php

namespace LTDBeget\Rush;


class Core
{

    /**
     * @var PrinterInterface
     */
    protected $printer;

    protected $outputFormatter;

    /**
     * Core constructor.
     * @param PrinterInterface $printer
     */
    public function __construct(PrinterInterface $printer)
    {
        $this->printer = $printer;
    }

}
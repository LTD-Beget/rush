<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\Printer;

class PrinterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @uses Printer::stylish()
     */
    public function testStylish()
    {
        $method = new \ReflectionMethod(Printer::class, 'stylish');
        $method->setAccessible(true);
        $actual = $method->invoke(new Printer(), 'some string', 'style');
        $expected = '<style>some string</style>';

        $this->assertEquals($expected, $actual);
    }

}

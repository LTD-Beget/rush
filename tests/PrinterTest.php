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
        $class = new \ReflectionClass(Printer::class);
        $method = $class->getMethod('stylish');
        $method->setAccessible(true);
        $actual = $method->invoke($class->newInstanceWithoutConstructor(), 'some string', 'style');
        $expected = '<style>some string</style>';

        $this->assertEquals($expected, $actual);
    }

}

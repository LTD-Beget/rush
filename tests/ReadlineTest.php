<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\Readline_OLD;

class ReadlineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected $mock;

    protected function setUp()
    {
        $this->mock = $this->getMockBuilder(Readline_OLD::class)->disableOriginalConstructor();
    }

    /**
     * @dataProvider getPrevProvider
     * @uses Readline_OLD::getLine()
     * @uses Readline_OLD::getPrev()
     */
    public function testGetPrev($line, $input, $expected)
    {
        $mock = $this->mock
            ->setMethods(['getLine'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getLine')
            ->willReturn($line);

        $getPrev = new \ReflectionMethod(Readline_OLD::class, 'getPrev');
        $getPrev->setAccessible(true);

        $this->assertEquals($expected, $getPrev->invoke($mock, $input));
    }

    /**
     * @uses Readline_OLD::info()
     * @uses Readline_OLD::getLine()
     */
    public function testGetLine()
    {
        $line = 'some line';

        $info = [
            'line_buffer' => $line,
            'end' => 9
        ];

        $mock = $this->mock
            ->setMethods(['info'])
            ->getMock();

        $mock->expects($this->once())
            ->method('info')
            ->willReturn($info);

        $getLine = new \ReflectionMethod(Readline_OLD::class, 'getLine');
        $getLine->setAccessible(true);

        $this->assertEquals($line, $getLine->invoke($mock));
    }

    public function getPrevProvider()
    {
        return [
            'few' => ['some line', 'line', 'some'],
            'one' => ['line', 'line', ''],
            'empty' => ['', '', '']
        ];
    }

}

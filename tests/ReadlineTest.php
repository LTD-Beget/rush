<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\Readline;

class ReadlineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected $mock;

    protected function setUp()
    {
        $this->mock = $this->getMockBuilder(Readline::class)->disableOriginalConstructor();
    }

    /**
     * @dataProvider getPrevProvider
     * @uses Readline::getLine()
     * @uses Readline::getPrev()
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

        $getPrev = new \ReflectionMethod(Readline::class, 'getPrev');
        $getPrev->setAccessible(true);

        $this->assertEquals($expected, $getPrev->invoke($mock, $input));
    }

    /**
     * @uses Readline::info()
     * @uses Readline::getLine()
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

        $getLine = new \ReflectionMethod(Readline::class, 'getLine');
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

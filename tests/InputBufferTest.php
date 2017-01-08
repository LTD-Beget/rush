<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\InputBuffer;


class InputBufferTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var InputBuffer
     */
    protected $obj;

    protected function setUp()
    {
        $this->obj = new InputBuffer();
    }

//    public function testGetInputCurrent()
//    {
//
//    }

    /**
     * @dataProvider countTokensProvider
     * @uses InputBuffer::countTokens()
     *
     * @param $value
     * @param $expected
     */
    public function testCountTokens($value, $expected)
    {
        $method = new \ReflectionMethod(InputBuffer::class, 'countTokens');
        $method->setAccessible(true);
        $actual = $method->invoke($this->obj, $value);

        $this->assertEquals($actual, $expected);
    }

    public function getInputCurrentProvider()
    {
        return [
            'single' => ['command', 'command'],
            'multi' => ['command arg', 'arg'],
            'with space' => ['command arg  ', ''],
            'with quoted' => ['command "some va', 3]
        ];
    }

    public function countTokensProvider()
    {
        return [
            'single' => ['command', 1],
            'multi' => ['command arg', 2],
            'with quoted and space' => ['command "some arg"   --name="some name" ', 3]
        ];
    }

}

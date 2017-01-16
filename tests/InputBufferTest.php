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

    /**
     * @uses InputBuffer::$input
     * @uses InputBuffer::$pos
     * @uses InputBuffer::insert()
     * @dataProvider insertProvider
     *
     * @param string $value
     * @param int $pos
     * @param string $insert
     * @param string $expectedValue
     * @param string $expectedPos
     */
    public function testInsert(string $value, int $pos, string $insert, string $expectedValue, string $expectedPos)
    {
        $class = new \ReflectionClass(InputBuffer::class);

        $propBuffer = $class->getProperty('buffer');
        $propBuffer->setAccessible(true);
        $propBuffer->setValue($this->obj, $value);

        $propPos = $class->getProperty('pos');
        $propPos->setAccessible(true);
        $propPos->setValue($this->obj, $pos);

        $method = $class->getMethod('insert');
        $method->invoke($this->obj, $insert);

        $this->assertEquals($expectedValue, $propBuffer->getValue($this->obj));
        $this->assertEquals($expectedPos, $propPos->getValue($this->obj));
    }


    public function insertProvider()
    {
        return [
            'insert to empty' => ['', 0, 'test', 'test', 4],
            'insert to end' => ['test', 4, 'done', 'testdone', 8],
            'insert to middle' => ['testdone', 4, 'middle', 'testmiddledone', 10],
            'insert to begin' => ['testmiddledone', 0, 'prefix', 'prefixtestmiddledone', 6],
        ];
    }


}

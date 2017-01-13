<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\ColumnFormatter;

class ColumnFormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ColumnFormatter
     */
    protected $obj;

    protected function setUp()
    {
        $this->obj = new ColumnFormatter();
    }

    /**
     * @dataProvider calculateColumnHeightProvider
     * @uses         ColumnFormatter::calculateHeightColumn()
     *
     * @param int $qty
     * @param int $height
     * @param int $width
     * @param int $widthRow
     * @param int $expected
     */
    public function testCalculateHeightColumn(int $qty, int $height, int $width, int $widthRow, int $expected)
    {
        $method = new \ReflectionMethod(ColumnFormatter::class, 'calculateHeightColumn');
        $method->setAccessible(true);
        $actual = $method->invoke($this->obj, $qty, $height, $width, $widthRow, $expected);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @uses ColumnFormatter::calculateWidthColumn()
     */
    public function testCalculateWidthColumn()
    {
        $method = new \ReflectionMethod(ColumnFormatter::class, 'calculateWidthColumn');
        $method->setAccessible(true);
        $actual = $method->invoke($this->obj, $this->fixture());

        $this->assertEquals(9, $actual);
    }

    /**
     * @dataProvider prepareProvider
     * @uses         ColumnFormatter::prepare()
     *
     * @param array $data
     * @param int $size
     * @param array $expected
     */
    public function testPrepare(array $data, int $size, array $expected)
    {
        $method = new \ReflectionMethod(ColumnFormatter::class, 'prepare');
        $method->setAccessible(true);
        $actual = $method->invoke($this->obj, $data, $size);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @uses ColumnFormatter::getOutput()
     */
    public function testGetOutput()
    {
        $method = new \ReflectionMethod(ColumnFormatter::class, 'getOutput');
        $method->setAccessible(true);

        $data = ['assets', 'log', 'backup', 'migrate', 'billing', 'network', 'cache', 'users', 'db', '', 'help', ''];
        $actual = $method->invoke($this->obj, $data, 10, 6);

        $expected = <<<EOL

assets    log       
backup    migrate   
billing   network   
cache     users     
db                  
help                

EOL;

        $this->assertEquals($expected, $actual);
    }

    public function calculateColumnHeightProvider()
    {
        // qty, height, width, widthRow, heightExpected
        $widthRow = 40;
        return [
            'can' => [20, 5, 9, $widthRow, 5],
            'can not 1' => [20, 4, 9, $widthRow, 5],
            'can not 2' => [30, 5, 11, $widthRow, 10],
        ];
    }


    public function prepareProvider()
    {
        $equalExpected = ['assets', 'help', 'backup', 'log', 'billing', 'migrate', 'cache', 'network', 'db', 'users'];
        $notEqualExpected = ['assets', 'log', 'backup', 'migrate', 'billing', 'network', 'cache', 'users', 'db', '', 'help', ''];

        return [
            'equal columns' => [$this->fixture(), 5, $equalExpected],
            'not equal columns' => [$this->fixture(), 6, $notEqualExpected],
        ];
    }

    protected function fixture()
    {
        return [
            'assets',
            'backup',
            'billing',
            'cache',
            'db',
            'help',
            'log',
            'migrate',
            'network',
            'users',
        ];
    }

}

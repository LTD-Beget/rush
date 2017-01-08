<?php

namespace LTDBeget\Rush\Tests;


use LTDBeget\Rush\InputBuffer;
use LTDBeget\Rush\InputInfo;


class InputInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var InputInfo
     */
    protected $obj;

    protected function setUp()
    {
//        $this->obj = new InputInfo('ffd');
    }

    /**
     * @dataProvider hasOpenedQuotesProvider
     * @uses InputInfo::hasOpenedQuotes()
     *
     * @param $value
     * @param $expected
     */
    public function testHasOpenedQuotes($value, $expected)
    {
        $method = new \ReflectionMethod(InputInfo::class, 'hasOpenedQuotes');
        $method->setAccessible(true);

        $obj = (new \ReflectionClass(InputInfo::class))->newInstanceWithoutConstructor();
        $actual = $method->invoke($obj, $value);
        $this->assertEquals($actual, $expected);
    }

    public function hasOpenedQuotesProvider()
    {
        return [
            'one' => ['"Los ', true],
            'three' => ['--city="New York" --country="United Sta', true],
            'without' => ['--city="Moscow"', false],
        ];
    }

    public function inputProvider()
    {
        return [

        ];
    }

}

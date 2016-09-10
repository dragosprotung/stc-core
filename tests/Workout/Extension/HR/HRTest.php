<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Extension\HR;

use SportTrackerConnector\Core\Workout\Extension\HR;

/**
 * Test for \SportTrackerConnector\Core\Workout\Workout\Extension\HR.
 */
class HRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get the ID.
     */
    public function testGetID()
    {
        self::assertSame('HR', HR::ID());
    }

    /**
     * Data provider for testSetValueValid();
     *
     * @return array
     */
    public function dataProviderTestSetValueValid()
    {
        return array(
            array(null),
            array(123),
            array(230)
        );
    }

    /**
     * Test set/get the value of an extension.
     *
     * @dataProvider dataProviderTestSetValueValid
     * @param mixed $value The value.
     */
    public function testSetValueValid($value)
    {
        $hr = new HR($value);

        self::assertSame($value, $hr->value());
    }

    /**
     * Data provider for testSetValueInvalid();
     *
     * @return array
     */
    public function dataProviderTestSetValueInvalid()
    {
        return array(
            array(''),
            array(123.123),
            array('some string'),
            array(array('123')),
            array(new \stdClass()),
            array(-1),
            array(231),
        );
    }

    /**
     * Test set/get the value of an extension.
     *
     * @dataProvider dataProviderTestSetValueInvalid
     * @param mixed $value The value.
     */
    public function testSetValueInvalid($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value for the HR must be an integer and between 0 and 230.');

        new HR($value);
    }
}

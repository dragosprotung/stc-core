<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Extension\HR;

use Assert\AssertionFailedException;
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
            array(40),
            array(210)
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
        $hr = HR::fromValue($value);

        self::assertSame($value, $hr->value());
    }

    /**
     * Data provider for testInvalidValues();
     *
     * @return array
     */
    public function dataProviderInvalidValues()
    {
        return array(
            array(null),
            array(''),
            array(123.123),
            array('some string'),
            array(array('123')),
            array(new \stdClass()),
            array(-1),
            array(211),
        );
    }

    /**
     * Test set/get the value of an extension.
     *
     * @dataProvider dataProviderInvalidValues
     * @param mixed $value The value.
     */
    public function testInvalidValues($value)
    {
        $this->expectException(AssertionFailedException::class);

        HR::fromValue($value);
    }
}

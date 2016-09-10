<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Extension\AbstractExtension;

use SportTrackerConnector\Core\Workout\Extension\AbstractExtension;

/**
 * Test for workout abstract extension.
 */
class AbstractExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testSetGetValue();
     *
     * @return array
     */
    public function dataProviderTestSetGetValue()
    {
        return array(
            array(null),
            array(''),
            array('some string'),
            array(123),
            array(123.456),
            array(array('123')),
            array(new \stdClass())
        );
    }

    /**
     * Test set/get the value of an extension.
     *
     * @dataProvider dataProviderTestSetGetValue
     * @param mixed $value The value.
     */
    public function testSetGetValue($value)
    {
        $mock = $this->getMockForAbstractClass(AbstractExtension::class, array($value));

        /** @var AbstractExtension $mock */
        self::assertSame($value, $mock->value());
    }
}

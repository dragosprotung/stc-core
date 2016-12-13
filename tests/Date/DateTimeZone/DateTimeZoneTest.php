<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Date\DateTimeZone;

use SportTrackerConnector\Core\Date\DateTimeZone;

/**
 * Test for DateTimeZone.
 */
class DateTimeZoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testGetTimeZoneOffsetProvider().
     *
     * @return array
     */
    public static function dataProviderGetUTCTimeZoneOffset(): array
    {
        return [
            [new DateTimeZone('UTC'), 0],
            [new DateTimeZone('Europe/Berlin'), self::isDST('Europe/Berlin') ? -7200 : -3600],
            [new DateTimeZone('Europe/Bucharest'), self::isDST('Europe/Bucharest') ? -10800 : -7200],
            [new DateTimeZone('America/Los_Angeles'), self::isDST('America/Los_Angeles') ? 25200 : 28800],
            [new DateTimeZone('Pacific/Auckland'), self::isDST('Pacific/Auckland') ? -46800 : -43200]
        ];
    }

    /**
     * Check if a timezone is in daylight saving time.
     *
     * @param string $timezone The timezone to check.
     * @return bool
     */
    private static function isDST(string $timezone): bool
    {
        $date = new \DateTime('now', new \DateTimeZone($timezone));
        return (boolean)$date->format('I');
    }

    /**
     * Test get UTC time zone offset.
     *
     * @param DateTimeZone $originTimeZone The origin timezone.
     * @param integer $expected The number of seconds expected to be the time zone difference.
     * @dataProvider dataProviderGetUTCTimeZoneOffset
     */
    public function testGetUTCTimeZoneOffset($originTimeZone, $expected)
    {
        self::assertEquals($expected, $originTimeZone->UTCTimeZoneOffset());
    }
}

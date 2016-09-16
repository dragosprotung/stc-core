<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Date\DateTimeZone;

use SportTrackerConnector\Core\Date\DateTimeZone;
use SportTrackerConnector\Core\Tracker\AbstractTracker;

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
    public function dataProviderGetUTCTimeZoneOffset()
    {
        $data = array();
        $data[] = array(new DateTimeZone('UTC'), 0);
        $data[] = array(new DateTimeZone('Europe/Berlin'), $this->isDST('Europe/Berlin') ? -7200 : -3600);
        $data[] = array(new DateTimeZone('Europe/Bucharest'), $this->isDST('Europe/Bucharest') ? -10800 : -7200);
        $data[] = array(new DateTimeZone('America/Los_Angeles'), $this->isDST('America/Los_Angeles') ? 25200 : 28800);
        $data[] = array(new DateTimeZone('Pacific/Auckland'), $this->isDST('Pacific/Auckland') ? -46800 : -43200);

        return $data;
    }

    /**
     * Check if a timezone is in daylight saving time.
     *
     * @param string $timezone The timezone to check.
     * @return boolean
     */
    private function isDST($timezone)
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
        /** @var AbstractTracker $mock */
        self::assertEquals($expected, $originTimeZone->UTCTimeZoneOffset());
    }
}

<?php

declare(strict_types = 1);

namespace SportTrackerConnector\Core\Tests\Workout\Track;

use DateTime;
use SportTrackerConnector\Core\Date\DateInterval;
use SportTrackerConnector\Core\Workout\Track;
use SportTrackerConnector\Core\Workout\TrackPoint;

/**
 * Test a workout track.
 */
class TrackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test recompute start date time.
     */
    public function testRecomputeStartDateTime()
    {
        $trackPoints = array(
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('now')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('-1 hour')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('2014-01-01 00:00:00')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('+1 hour'))
        );
        $track = new Track($trackPoints);

        /** @var Track $track */
        $actual = $track->startDateTime();

        $expected = new DateTime('2014-01-01 00:00:00');
        self::assertEquals($expected, $actual);
    }

    /**
     * Test recompute start date time.
     */
    public function testRecomputeEndDateTime()
    {
        $trackPoints = array(
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('now')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('-1 hour')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('2014-01-01 00:00:00')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('2034-01-01 00:00:00'))
        );
        $track = new Track($trackPoints);

        /** @var Track $track */
        $actual = $track->endDateTime();

        $expected = new DateTime('2034-01-01 00:00:00');
        self::assertEquals($expected, $actual);
    }

    /**
     * Test the get duration.
     */
    public function testGetDuration()
    {
        $trackPoints = array(
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('now')),
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('+1 hour +5 minutes +20 seconds')),
        );
        $track = new Track($trackPoints);

        $expected = new DateInterval('PT1H5M20S');
        $actual = $track->duration();

        self::assertEquals($expected, $actual);
        self::assertSame('1h:5m:20s', $actual->format('%hh:%im:%ss'));
    }

    /**
     * Test recompute the length.
     */
    public function testRecomputeLength()
    {
        $trackPoints = array(
            TrackPoint::with(-38.691450, 176.079795, new \DateTimeImmutable('now')),
            TrackPoint::with(-38.719038, 176.081491, new \DateTimeImmutable('now')),
            TrackPoint::with(-38.810918, 176.087366, new \DateTimeImmutable('now')),
            TrackPoint::with(-38.997640, 176.082147, new \DateTimeImmutable('now'))
        );
        $track = new Track($trackPoints);

        $expected = 34067.903477;

        self::assertEquals($expected, $track->length());
    }

    /**
     * Test recompute length returns zero if less than two points.
     */
    public function testRecomputeLengthReturnsZeroIfLessThankTwoPoints()
    {
        $trackPoints = array(
            TrackPoint::with(0.0, 0.0, new \DateTimeImmutable('now'))
        );
        $track = new Track($trackPoints);

        self::assertEquals(0, $track->length());
    }
}

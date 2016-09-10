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
     * Test that calling the getStartDateTime() will trigger the recomputing if is not yet set.
     */
    public function testGetStartDateTimeCallsRecomputeIfDateTimeNotSet()
    {
        $track = $this->createPartialMock(Track::class, array('recomputeStartDateTime'));
        $track
            ->expects(self::once())
            ->method('recomputeStartDateTime');

        /** @var Track $track */
        $track->getStartDateTime();
    }

    /**
     * Test that calling the getEndDateTime() will trigger the recomputing if is not yet set.
     */
    public function testGetEndDateTimeCallsRecomputeIfDateTimeNotSet()
    {
        $track = $this->createPartialMock(Track::class, array('recomputeEndDateTime'));
        $track
            ->expects(self::once())
            ->method('recomputeEndDateTime');

        /** @var Track $track */
        $track->getEndDateTime();
    }

    /**
     * Test that calling the getLength() will trigger the recomputing if is not yet set.
     */
    public function testGetLengthCallsRecomputeIfLengthIsNotSet()
    {
        $track = $this->createPartialMock(Track::class, array('recomputeLength'));
        $track
            ->expects(self::once())
            ->method('recomputeLength');

        /** @var Track $track */
        $track->getLength();
    }

    /**
     * Test recompute start date time.
     */
    public function testRecomputeStartDateTime()
    {
        $track = $this->createPartialMock(Track::class, array('getTrackPoints'));
        $trackPoints = array(
            $this->getTrackPointMock(0, 0, 'now'),
            $this->getTrackPointMock(0, 0, '-1 hour'),
            $this->getTrackPointMock(0, 0, '2014-01-01 00:00:00'),
            $this->getTrackPointMock(0, 0, '+1 hour')
        );
        $track
            ->expects(self::once())
            ->method('getTrackPoints')
            ->will(self::returnValue($trackPoints));

        /** @var Track $track */
        $actual = $track->getStartDateTime();

        $expected = new DateTime('2014-01-01 00:00:00');
        self::assertEquals($expected, $actual);
    }

    /**
     * Test recompute start date time.
     */
    public function testRecomputeEndDateTime()
    {
        $track = $this->createPartialMock(Track::class, array('getTrackPoints'));
        $trackPoints = array(
            $this->getTrackPointMock(0, 0, 'now'),
            $this->getTrackPointMock(0, 0, '-1 hour'),
            $this->getTrackPointMock(0, 0, '2014-01-01 00:00:00'),
            $this->getTrackPointMock(0, 0, '2034-01-01 00:00:00')
        );
        $track
            ->expects(self::once())
            ->method('getTrackPoints')
            ->will(self::returnValue($trackPoints));

        /** @var Track $track */
        $actual = $track->getEndDateTime();

        $expected = new DateTime('2034-01-01 00:00:00');
        self::assertEquals($expected, $actual);
    }

    /**
     * Test the get duration.
     */
    public function testGetDuration()
    {
        $track = $this->createPartialMock(Track::class, array('getStartDateTime', 'getEndDateTime'));
        $startDateTime = new DateTime('now');
        $endDateTime = new DateTime('+1 hour +5 minutes +20 seconds');
        $track->expects(self::once())->method('getStartDateTime')->will(self::returnValue($startDateTime));
        $track->expects(self::once())->method('getEndDateTime')->will(self::returnValue($endDateTime));

        $expected = new DateInterval('PT1H5M20S');
        /** @var Track $track */
        $actual = $track->getDuration();

        self::assertEquals($expected, $actual);
        self::assertSame('1h:5m:20s', $actual->format('%hh:%im:%ss'));
    }

    /**
     * Test recompute the length.
     */
    public function testRecomputeLength()
    {
        $track = $this->createPartialMock(Track::class, array('getTrackPoints'));
        $trackPoints = array(
            $this->getTrackPointMock(-38.691450, 176.079795),
            $this->getTrackPointMock(-38.719038, 176.081491),
            $this->getTrackPointMock(-38.810918, 176.087366),
            $this->getTrackPointMock(-38.997640, 176.082147)
        );
        $track
            ->expects(self::once())
            ->method('getTrackPoints')
            ->will(self::returnValue($trackPoints));

        $expected = 34067.903477;
        /** @var Track $track */
        $actual = $track->recomputeLength();

        self::assertEquals($expected, $actual);
    }

    /**
     * Test recompute length returns zero if less than two points.
     */
    public function testRecomputeLengthReturnsZeroIfLessThankTwoPoints()
    {
        $track = $this->createPartialMock(Track::class, array('getTrackPoints'));
        $trackPoints = array(
            $this->getTrackPointMock(0, 0, 'now')
        );
        $track
            ->expects(self::exactly(2))
            ->method('getTrackPoints')
            ->will(self::returnValue($trackPoints));

        /** @var Track $track */
        $track->setLength(100);
        $actual = $track->recomputeLength();

        self::assertEquals(0, $actual);
        self::assertEquals(0, $track->getLength());
    }

    /**
     * Get a track point mock.
     *
     * @param float $lat The latitude.
     * @param float $lon The longitude.
     * @param string $dateTime The date and time of the point.
     * @return TrackPoint
     */
    private function getTrackPointMock(float $lat, float $lon, string $dateTime = '')
    {
        return new TrackPoint($lat, $lon, new DateTime($dateTime));
    }
}
